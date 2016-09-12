<template>
    <div v-show="shared.showFilters" id="exercise-filters">
        <div class="flex">

            <div class="form-group">
                <label for="filter-by-name">Name</label>
                <input
                    v-model="filters.name"
                    type="text"
                    id="filter-by-name"
                    name="filter-by-name"
                    placeholder="name"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="filter-by-description">Description</label>
                <input
                    v-model="filters.description"
                    type="text"
                    id="filter-by-description"
                    name="filter-by-description"
                    placeholder="description"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <label for="filter-by-series">Series</label>

                <select
                    v-model="filters.series"
                    id="filter-by-series"
                    class="form-control"
                >
                    <option value="all">All</option>
                    <option
                        v-for="series in exerciseSeries"
                        v-bind:value="series.name"
                    >
                        {{ series.name }}
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="filter-by-priority">Priority</label>
                <input
                    v-model="filters.priority"
                    type="text"
                    id="filter-by-priority"
                    name="filter-by-priority"
                    placeholder="priority"
                    class="form-control"
                >
            </div>

            <div class="form-group">
                <div class="checkbox-container">
                    <input
                        v-model="filters.showStretches"
                        id="show-stretches"
                        type="checkbox"
                    >
                    <label for="show-stretches-">Show stretches</label>
                </div>
            </div>

            <button v-on:click="hideFilters()" class="btn btn-success">Done</button>

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
        methods: {
            /**
             *
             */
            hideFilters: function () {
                store.set(false, 'showFilters');
            }
        },
        props: [
            'filters'
        ]
    };
</script>