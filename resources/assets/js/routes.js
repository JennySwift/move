
import Vue from 'vue'

import ExercisesPage from './components/ExercisesPageComponent.vue'
import ExercisePage from './components/ExercisePageComponent.vue'
import NewExercisePage from './components/NewExerciseComponent.vue'
import NewWorkoutPage from './components/NewWorkoutPageComponent.vue'
import WorkoutsPage from './components/WorkoutsPageComponent.vue'
import EditWorkoutPage from './components/EditWorkoutPageComponent.vue'
import EditSessionPage from './components/EditSessionPageComponent.vue'
import SessionsPage from './components/SessionsPageComponent.vue'
import UnitsPage from './components/ExerciseUnitsPageComponent.vue'
import ExerciseHistoryPage from './components/ExerciseHistoryPageComponent.vue'

var on = {
    pageAfterIn: function (e, page) {
        store.updateRouteHistory(page.route.path);
    }
};

export default [
    {
        name: 'exercises',
        path: '/exercises',
        component: ExercisesPage,
        on: on
    },
    {
        name: 'exercise',
        path: '/exercises/:id',
        component: ExercisePage,
        on: on
    },
    {
        name: 'exercise history',
        path: '/exercises/:id/history',
        component: ExerciseHistoryPage,
        on: on
    },
    {
        name: 'add exercise',
        path: '/add-exercise',
        component: NewExercisePage,
        on: on
    },
    {
        name: 'sessions',
        path: '/sessions',
        component: SessionsPage,
        alias: '/',
        on: on
    },
    {
        name: 'units',
        path: '/units',
        component: UnitsPage,
        on: on
    },
    {
        name: 'workouts',
        path: '/workouts',
        component: WorkoutsPage,
        on: on
    },
    {
        name: 'add workout',
        path: '/add-workout',
        component: NewWorkoutPage,
        on: on
    },
    {
        name: 'workout',
        path: '/workouts/:id',
        component: EditWorkoutPage,
        on: on
    },
    {
        name: 'session',
        path: '/sessions/:id',
        component: EditSessionPage,
        on: on
    },
]


