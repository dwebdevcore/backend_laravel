<template>
    <div id="image-box" v-if="images.length > 0">
        <gallery :images="images" :options="{closeOnSlideClick: true, onslideend: makeLabel}" :index="index" @close="index = null"></gallery>
        <div v-for="(image, imageIndex) in images" style="width: 300px; height: 240px;" class="image-b">
            <div :key="imageIndex" @click="index = imageIndex" v-bind:class="[cover === imageIndex ? 'coverClass' : '', 'image']" :style="{ backgroundImage: 'url(' + image.href + ')', width: '300px', height: '200px' }"></div>
            <button class="set-cover" v-on:click.prevent="setCover(imageIndex)" >Set cover</button>
        </div>
    </div>
    <div v-else class="flex justify-center items-center px-6 py-8">
        <div class="text-center">
            <svg class="mb-3" xmlns="http://www.w3.org/2000/svg" width="65" height="51" viewBox="0 0 65 51"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="05-blank-state" fill="#A8B9C5" fill-rule="nonzero" transform="translate(-779 -695)"><path id="Combined-Shape" d="M835 735h2c.552285 0 1 .447715 1 1s-.447715 1-1 1h-2v2c0 .552285-.447715 1-1 1s-1-.447715-1-1v-2h-2c-.552285 0-1-.447715-1-1s.447715-1 1-1h2v-2c0-.552285.447715-1 1-1s1 .447715 1 1v2zm-5.364125-8H817v8h7.049375c.350333-3.528515 2.534789-6.517471 5.5865-8zm-5.5865 10H785c-3.313708 0-6-2.686292-6-6v-30c0-3.313708 2.686292-6 6-6h44c3.313708 0 6 2.686292 6 6v25.049375c5.053323.501725 9 4.765277 9 9.950625 0 5.522847-4.477153 10-10 10-5.185348 0-9.4489-3.946677-9.950625-9zM799 725h16v-8h-16v8zm0 2v8h16v-8h-16zm34-2v-8h-16v8h16zm-52 0h16v-8h-16v8zm0 2v4c0 2.209139 1.790861 4 4 4h12v-8h-16zm18-12h16v-8h-16v8zm34 0v-8h-16v8h16zm-52 0h16v-8h-16v8zm52-10v-4c0-2.209139-1.790861-4-4-4h-44c-2.209139 0-4 1.790861-4 4v4h52zm1 39c4.418278 0 8-3.581722 8-8s-3.581722-8-8-8-8 3.581722-8 8 3.581722 8 8 8z"/></g></g></svg>

            <h3 class="text-base text-80 font-normal mb-6">
                {{__('No images found')}}
            </h3>
        </div>
    </div>
</template>

<script>
    import VueGallery from 'vue-gallery';

    export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    mounted() {
        this.init();
    },
    data: function () {
        return {
            images: [],
            index: null,
            carousel: true,
            cover: null,
            labels: []
        }
    },
    components: {
        'gallery': VueGallery
    },
    methods: {
        makeLabel: function (index) {
            let divs = document.getElementsByClassName("description");
            for (let i = 0; i < divs.length; i++) {
                divs[i].innerHTML = this.labels[index];
            }
        },
        init: function () {
            let coverCounter = 0;
            for (let i = 0; i < this.field.value.length; i++) {
                let images = this.field.value[i].images;
                for (let j = 0; j < images.length; j++) {
                    if (images[j]['cover']) {
                        this.cover = coverCounter;
                    }

                    this.images.push({
                        'href': images[j]['url']
                    });
                    this.labels[i] = [];
                    this.labels[i].push(images[j].labels.map(function (val) {
                        let _class = '';
                        if (val.confidence >= 75) {
                            _class = 'green';
                        } else if (val.confidence >= 50 && val.confidence < 75) {
                            _class = 'orange';
                        } else if (val.confidence >= 25 && val.confidence < 50) {
                            _class = 'gray';
                        } else {
                            _class = 'red';
                        }
                        return "<span class='label "+_class+"'>" + val.label + "("+val.confidence+"%)" + "</span>";
                    }).join(""));
                    coverCounter++;
                }
            }
        },
        setCover: function (index) {
            this.cover = index;
            Nova.request().post('/api/v1/business-cover', {
                id: this.field.value[index].id,
            }).then(response => {});

            return false;
        }
    }
}
</script>
