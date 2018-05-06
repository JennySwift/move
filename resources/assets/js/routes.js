import ExercisesPage from './components/ExercisesPageComponent.vue'
import ExercisePage from './components/ExercisePageComponent.vue'
import NewExercisePage from './components/NewExerciseComponent.vue'
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
        path: '/exercises/#/add',
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


]