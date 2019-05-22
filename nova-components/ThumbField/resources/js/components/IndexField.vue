<template>
    <div>
        <div id="modal-div" class="modal">
            <span class="close" @click="close">&times;</span>
            <img class="modal-content" v-bind:src="field.value" id="img01">
        </div>
        <img id="img" @click="open" v-bind:src="field.value" alt="" class="rounded-full w-8 h-8 thumb_field">
    </div>
</template>

<script>

export default {
    props: ['resourceName', 'field'],
    mounted() {
        this.init();
    },
    methods: {
        init: function () {
            document.onkeydown = (evt) => {
                evt = evt || window.event;
                if ("key" in evt) {
                    if (evt.key === "Escape" || evt.key === "Esc") {
                        this.close();
                    }
                }
            };
            document.onclick = (e) => {
                if(e.target.id === 'modal') {
                    this.close();
                }
            };
        },
        open: function () {
            document.getElementById('modal').style.display = "block";
        },
        close: function () {
            document.getElementById('modal').style.display = "none";
        }
    }
}
</script>
