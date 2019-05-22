Nova.booting((Vue, router) => {
    Vue.component('index-ImageBox', require('./components/IndexField'));
    Vue.component('detail-ImageBox', require('./components/DetailField'));
    Vue.component('form-ImageBox', require('./components/FormField'));
})
