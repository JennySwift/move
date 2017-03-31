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
    '/exercises/#/add': {
        component: require('./components/NewExerciseComponent.vue')
    },
    '/entries': {
        component: require('./components/ExerciseEntriesComponent.vue')
    },
    '/entries/#/add': {
        component: require('./components/NewExerciseEntryComponent.vue')
    },
    '/exercises/:exerciseId/units/:unitId/:date': {
        name: 'entriesForSpecificExerciseAndDateAndUnit',
        component: require('./components/EntriesForSpecificExerciseAndDateAndUnitComponent.vue')
    },
    '/series/#/add': {
        component: require('./components/NewSeriesComponent.vue')
    },
    '/series': {
        component: require('./components/SeriesComponent.vue')
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