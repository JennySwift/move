<template>
    <div v-show="shared.showFilters" id="exercise-filters">
        <div class="input-group-container">
            <input-group
                label="Name:"
                :model.sync="shared.exerciseFilters.name"
                :enter="hideFilters"
                id="filter-by-name"
            >
            </input-group>

            <input-group
                label="Description:"
                :model.sync="shared.exerciseFilters.description"
                :enter="hideFilters"
                id="filter-by-description"
            >
            </input-group>

            <input-group
                label="Series:"
                :model.sync="shared.exerciseFilters.series"
                :enter="hideFilters"
                id="filter-by-series"
                :options="exerciseSeriesOptions"
                options-prop="name"
            >
            </input-group>

            <input-group
                label="Priority:"
                :model.sync="shared.exerciseFilters.priority"
                :enter="hideFilters"
                id="filter-by-priority"
            >
            </input-group>

            <checkbox-group
                label="Show Stretches:"
                :model.sync="shared.exerciseFilters.showStretches"
                id="show-stretches"
            >
            </checkbox-group>

            <div class="form-group">
                <button v-on:click="hideFilters()" class="btn btn-success">Done</button>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data: function () {
            return {
                shared: store.state
            }
        },
        computed: {
            exerciseSeriesOptions: function () {
                var options = helpers.clone(this.shared.exerciseSeries);
                options.unshift({name: 'Any', value: 'all'});
                return options;
            }
        },
        methods: {
            /**
             *
             */
            hideFilters: function () {
                store.set(false, 'showFilters');
            }
        }
    };
</script>