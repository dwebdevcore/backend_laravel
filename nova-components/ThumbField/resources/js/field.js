Nova.booting((Vue, router) => {
    Vue.component('index-ThumbField', require('./components/IndexField'));
    Vue.component('detail-ThumbField', require('./components/DetailField'));
    Vue.component('form-ThumbField', require('./components/FormField'));
})
