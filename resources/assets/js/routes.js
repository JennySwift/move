
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
// import HomePage from './components/HomePageComponent.vue'

Vue.component('home-page', {
    template: '#home-page'
});

export default [
    {
        name: 'home',
        path: '/',
        component: 'home-page'
    },
    {
        name: 'exercises',
        path: '/exercises',
        component: ExercisesPage
    },
    {
        name: 'exercise',
        path: '/exercises/:id',
        component: ExercisePage
    },
    {
        name: 'add exercise',
        path: '/add-exercise',
        component: NewExercisePage
    },
    {
        name: 'sessions',
        path: '/sessions',
        component: SessionsPage
    },
    {
        name: 'units',
        path: '/units',
        component: UnitsPage
    },
    {
        name: 'workouts',
        path: '/workouts',
        component: WorkoutsPage
    },
    // {
    //     path: '/workouts/:id',
    //     component: WorkoutPage
    // },
    // {
    //     path: '/sessions/:id',
    //     component: SessionPage
    // },
    {
        name: 'add workout',
        path: '/add-workout',
        component: NewWorkoutPage
    },
    {
        name: 'workout',
        path: '/workouts/:id/edit',
        component: EditWorkoutPage
    },
    {
        name: 'session',
        path: '/sessions/:id/edit',
        component: EditSessionPage
    },
]


