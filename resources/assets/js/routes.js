import ExercisesPage from './components/ExercisesPageComponent.vue'
import ExercisePage from './components/ExercisePageComponent.vue'
import NewExercisePage from './components/NewExerciseComponent.vue'
import NewWorkoutPage from './components/NewWorkoutPageComponent.vue'
import WorkoutsPage from './components/WorkoutsPageComponent.vue'
import WorkoutPage from './components/WorkoutPageComponent.vue'
import EditWorkoutPage from './components/EditWorkoutPageComponent.vue'
import EntriesPage from './components/ExerciseEntriesComponent.vue'
import NewEntryPage from './components/NewExerciseEntryComponent.vue'
import EntriesForSpecificExerciseAndDateAndUnit from './components/EntriesForSpecificExerciseAndDateAndUnitComponent.vue'
import UnitsPage from './components/ExerciseUnitsPageComponent.vue'
import SeriesPage from './components/SeriesComponent.vue'
import NewSeriesPage from './components/NewSeriesComponent.vue'

export default [
    {
        path: '/',
        component: ExercisesPage
    },
    {
        path: '/exercises',
        component: ExercisesPage
    },
    {
        path: '/exercises/:id',
        component: ExercisePage
    },
    {
        path: '/add-exercise',
        component: NewExercisePage
    },
    {
        path: '/entries',
        component: EntriesPage
    },
    {
        path: '/entries/#/add',
        component: NewEntryPage
    },
    {
        path: '/exercises/:exerciseId/units/:unitId/:date',
        component: EntriesForSpecificExerciseAndDateAndUnit,
        name: 'entriesForSpecificExerciseAndDateAndUnit',
    },
    {
        path:  '/series/#/add',
        component: NewSeriesPage
    },
    {
        path: '/series',
        component: SeriesPage
    },
    {
        path: '/units',
        component: UnitsPage
    },
    {
        path: '/workouts',
        component: WorkoutsPage
    },
    {
        path: '/workouts/:id',
        component: WorkoutPage
    },
    {
        path: 'add-workout',
        component: NewWorkoutPage
    },
    {
        path: '/workouts/:id/edit',
        component: EditWorkoutPage
    },


]