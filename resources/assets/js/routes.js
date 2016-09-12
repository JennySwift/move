var Vue = require('vue');
var VueRouter = require('vue-router');
Vue.use(VueRouter);
global.router = new VueRouter({hashbang: false});

router.map({
    '/exercises': {
        component: require('./components/ExercisesPageComponent.vue')
    },
    '/exercises/:id': {
        component: require('./components/ExercisePageComponent.vue')
    },
    '/exercise-units': {
        component: require('./components/ExerciseUnitsPageComponent.vue')
    }
});

router.redirect({
    '/': '/exercises'
});

var App = Vue.component('app', require('./components/AppComponent'));

router.start(App, 'body');