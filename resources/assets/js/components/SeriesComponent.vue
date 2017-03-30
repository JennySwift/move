<template>
    <div id="series-page">
        <ul v-for="series in shared.exerciseSeries"class="list-group">
            <li v-bind:style="{background: series.color}" v-bind:class="{'has-color': series.color}" class="list-group-item series">{{series.name}}</li>
        </ul>

    </div>

</template>

<script>
    module.exports = {
        template: 'series-template',
        data: function () {
            return {
                shared: store.state,
            };
        },
        components: {},
        methods: {

        },
        ready: function () {

        }
    };
</script>

