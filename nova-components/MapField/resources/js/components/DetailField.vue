<template>
    <card class="flex flex-col items-center justify-center">
        <div id='map'></div>
    </card>
</template>

<script>
import mapboxgl from 'mapbox-gl';
require('../../../node_modules/mapbox-gl/dist/mapbox-gl.css');

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data() {
        return {
            apiKey: 'pk.eyJ1IjoiYXNzZCIsImEiOiJjam4waHV1M2kwYXRpM3VwYzYyaTV6em5wIn0.JuV5MaCB2t0sdAgwxrJVbQ',
            url: '',
        };
    },
    mounted() {
        this.init();
    },
    methods: {
        init() {
            mapboxgl.accessToken = this.apiKey;
            this.map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v9',
                minzoom: 1.0,
                center: [this.field.value[0],this.field.value[1]],
                zoom: 11,
            });

            new mapboxgl.Marker({}).setLngLat([this.field.value[0],this.field.value[1]]).addTo(this.map);
        }
    },
}
</script>
