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
                label="Priority:"
                :model.sync="shared.exerciseFilters.priority"
                :enter="hideFilters"
                id="filter-by-priority"
            >
            </input-group>

            <div class="form-group">
                <button v-on:click="hideFilters()" class="btn btn-success">Done</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state
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
    }
</script>

<style lang="scss" type="text/scss">

</style>