<template>
    <card class="flex flex-col items-center justify-center">
        <div id='map'></div>
        <div id="stats">
            <table>
                <tr>
                    <td>
                        <b>{{__('Total businesses:')}}</b>
                    </td>
                    <td id="totalBusinesses">
                        {{ totalBusinesses }}
                    </td>
                    <td>
                        <b>{{__('Total reviews:')}}</b>
                    </td>
                    <td id="totalReviews">
                        {{ totalReviews }}
                    </td>
                    <td>
                        <b>{{__('Total images:')}}</b>
                    </td>
                    <td id="totalImages">
                        {{ totalImages }}
                    </td>
                </tr>
            </table>
        </div>
        <button v-if="!isHiddenAttributes" class="show_attributes" v-on:click="isHiddenAttributes = !isHiddenAttributes">Hide Attributes</button>
        <button v-else class="show_attributes" v-on:click="isHiddenAttributes = !isHiddenAttributes">Show Attributes</button>
        <div v-if="!isHiddenAttributes" id="attributes" v-html="attributes"></div>
    </card>
</template>

<script>
import mapboxgl from "mapbox-gl";
import MapboxGeocoder from "@mapbox/mapbox-gl-geocoder";
import * as Cookie from "js-cookie";
import queryString from "query-string";

import "../../../node_modules/mapbox-gl/dist/mapbox-gl.css";
import "../../../node_modules/@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css";

const API_KEY =
  "pk.eyJ1IjoiYXNzZCIsImEiOiJjam4waHV1M2kwYXRpM3VwYzYyaTV6em5wIn0.JuV5MaCB2t0sdAgwxrJVbQ";

export default {
  props: ["card"],
  data() {
    return {
      isHiddenAttributes: false,
      attributes: '',
      totalBusinesses: 0,
      totalReviews: 0,
      totalImages: 0,
      map: null
    };
  },
  mounted() {
    this.init();
  },
  methods: {
    init() {
      this.createMap();
    },
    setCookie(name, value, hours = 1) {
      Cookie.set(name, value, { expires: 1 });
    },
    getCookie(name) {
      return Cookie.get(name);
    },
    getCenter() {
      if (!this.getCookie("map_position")) {
        return {
          zoom: 7,
          center: {
            lat: 51.877827,
            lng: 0.764215
          }
        };
      }

      return JSON.parse(this.getCookie("map_position"));
    },
    applyFilters({ useCoords } = {}) {
      const getBounds = () => {
        let topLeftLat = this.map.getBounds().getNorthWest().lat,
          topLeftLng = this.map.getBounds().getNorthWest().lng,
          bottomRightLat = this.map.getBounds().getSouthEast().lat,
          bottomRightLng = this.map.getBounds().getSouthEast().lng;

        if (topLeftLat > 90) {
          topLeftLat = 90;
        }
        if (topLeftLat < -90) {
          topLeftLat = -90;
        }
        if (topLeftLng > 180) {
          topLeftLng = 180;
        }
        if (topLeftLng < -180) {
          topLeftLng = -180;
        }
        if (bottomRightLat > 90) {
          bottomRightLat = 90;
        }
        if (bottomRightLat < -90) {
          bottomRightLat = -90;
        }
        if (bottomRightLng > 180) {
          bottomRightLng = 180;
        }
        if (bottomRightLng < -180) {
          bottomRightLng = -180;
        }

        return {
          "top_left[lat]": topLeftLat,
          "top_left[lng]": topLeftLng,
          "bottom_right[lat]": bottomRightLat,
          "bottom_right[lng]": bottomRightLng
        };
      };

      const opts = {
        ...this.$route.query
      };

      if (useCoords) {
        Object.assign(opts, getBounds());
      }

      return queryString.stringify(opts);
    },
    getGeoJsonUrl() {
      return `/api/v1/businesses/geo-json?${this.applyFilters()}`;
    },
    getStatsUrl() {
      return `/api/v1/businesses/stats?${this.applyFilters({
        useCoords: true
      })}`;
    },
    createMap() {
      mapboxgl.accessToken = API_KEY;

      this.map = new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/mapbox/streets-v9",
        minZoom: 4,
        center: [this.getCenter().center.lng, this.getCenter().center.lat],
        zoom: this.getCenter().zoom
      });

      console.log(this.getStatsUrl());

      this.addClusters();
    },
    addClusters: function() {
      let map = this.map;
      map.addControl(new mapboxgl.NavigationControl());
      map.addControl(
        new MapboxGeocoder({
          accessToken: mapboxgl.accessToken
        })
      );

      this.map.on("load", () => {
        Nova.request()
          .get(this.getStatsUrl())
          .then(response => {
            this.totalImages = response.data.totalImages;
            this.totalReviews = response.data.totalReviews;
            this.totalBusinesses = response.data.totalBusinesses;
            this.attributes = response.data.attributes;
          });

        map.addSource("places", {
          type: "geojson",
          data: this.getGeoJsonUrl(),
          cluster: true,
          clusterMaxZoom: 14,
          clusterRadius: 50
        });
        map.addLayer({
          id: "clusters",
          type: "circle",
          source: "places",
          filter: ["has", "point_count"],
          paint: {
            "circle-color": [
              "step",
              ["get", "point_count"],
              "#51bbd6",
              100,
              "#f1f075",
              750,
              "#f28cb1"
            ],
            "circle-radius": [
              "step",
              ["get", "point_count"],
              20,
              100,
              30,
              750,
              40
            ]
          }
        });
        map.addLayer({
          id: "cluster-count",
          type: "symbol",
          source: "places",
          filter: ["has", "point_count"],
          layout: {
            "text-field": "{point_count_abbreviated}",
            "text-font": ["DIN Offc Pro Medium", "Arial Unicode MS Bold"],
            "text-size": 12
          }
        });
        map.addLayer({
          id: "unclustered-point",
          type: "circle",
          source: "places",
          filter: ["!", ["has", "point_count"]],
          paint: {
            "circle-color": "#da0913",
            "circle-radius": 4,
            "circle-stroke-width": 1,
            "circle-stroke-color": "#fff"
          }
        });
        map.on("click", "clusters", function(e) {
          var features = map.queryRenderedFeatures(e.point, {
            layers: ["clusters"]
          });
          var clusterId = features[0].properties.cluster_id;
          map
            .getSource("places")
            .getClusterExpansionZoom(clusterId, function(err, zoom) {
              if (err) return;

              map.easeTo({
                center: features[0].geometry.coordinates,
                zoom: zoom
              });
            });
        });
        map.on("mouseenter", "clusters", function() {
          map.getCanvas().style.cursor = "pointer";
        });
        map.on("mouseleave", "clusters", function() {
          map.getCanvas().style.cursor = "";
        });
        map.on("mouseenter", "unclustered-point", function() {
          map.getCanvas().style.cursor = "pointer";
        });
        map.on("click", "unclustered-point", function(e) {
          var coordinates = e.features[0].geometry.coordinates.slice();
          var description = e.features[0].properties.name;

          while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
          }

          new mapboxgl.Popup()
            .setLngLat(coordinates)
            .setHTML(description)
            .addTo(map);
        });

        map.on(
          "zoomend",
          function() {
            this.setCookie(
              "map_position",
              JSON.stringify({
                zoom: map.getZoom(),
                center: map.getCenter()
              })
            );

            Nova.request()
              .get(this.getStatsUrl())
              .then(response => {
                this.totalImages = response.data.totalImages;
                this.totalReviews = response.data.totalReviews;
                this.totalBusinesses = response.data.totalBusinesses;
                this.attributes = response.data.attributes;
              });
          }.bind(this)
        );

        map.on(
          "dragend",
          function() {
            this.setCookie(
              "map_position",
              JSON.stringify({
                zoom: map.getZoom(),
                center: map.getCenter()
              })
            );

            Nova.request()
              .get(this.getStatsUrl())
              .then(response => {
                this.totalImages = response.data.totalImages;
                this.totalReviews = response.data.totalReviews;
                this.totalBusinesses = response.data.totalBusinesses;
                this.attributes = response.data.attributes;
              });
          }.bind(this)
        );
      });
    }
  }
};
</script>
