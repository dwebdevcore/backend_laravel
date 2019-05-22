Nova.booting((Vue, router) => {
    Vue.component('index-map-field', require('./components/IndexField'));
    Vue.component('detail-map-field', require('./components/DetailField'));
    Vue.component('form-map-field', require('./components/FormField'));
})
