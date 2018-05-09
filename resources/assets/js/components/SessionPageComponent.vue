<!--<template>-->
    <!--<div id="session-page">-->

        <!--<div class="top-bar">-->
            <!--<h1>{{shared.session.name}}</h1>-->
            <!--<router-link to="/sessions" tag="i" class="close far fa-times-circle fa-2x"></router-link>-->
        <!--</div>-->

        <!--<div class="container">-->

            <!--<div v-for="exercise in sortedExercises">-->
                <!--{{exercise[0].name}}-->
                <!--<table class="table table-striped">-->
                    <!--<thead>-->
                    <!--<tr>-->
                        <!--<th>LEVEL</th>-->
                        <!--<th>{{exercise[0].unit.data.name}}</th>-->
                    <!--</tr>-->
                    <!--</thead>-->
                    <!--<tbody>-->
                    <!--<tr v-for="row in exercise">-->
                        <!--<td>{{row.level}}</td>-->
                        <!--<td>{{row.quantity}}</td>-->
                    <!--</tr>-->
                    <!--</tbody>-->
                <!--</table>-->
            <!--</div>-->

            <!--<router-link :to="'/sessions/' + shared.session.id + '/edit'" tag="button" id="edit-session-btn" class="new-btn btn btn-default">Edit Session</router-link>-->
        <!--</div>-->

    <!--</div>-->
<!--</template>-->

<!--<script>-->
    <!--export default {-->
        <!--data: function () {-->
            <!--return {-->
                <!--shared: store.state,-->
                <!--baseUrl: 'api/sessions'-->
            <!--}-->
        <!--},-->
        <!--computed: {-->
            <!--sortedExercises: function () {-->
                <!--if (this.shared.session.exercises) {-->
                    <!--var sorted = _.groupBy(this.shared.session.exercises.data, 'name');-->
                    <!--console.log(sorted);-->
                    <!--return sorted;-->
                <!--}-->
            <!--}-->
        <!--},-->
        <!--methods: {-->
            <!--/**-->
             <!--*-->
             <!--*/-->
            <!--getSession: function () {-->
                <!--var id = this.$route.params.id;-->

                <!--helpers.get({-->
                    <!--url: this.baseUrl + '/' + id + '?include=exercises',-->
                    <!--storeProperty: 'session'-->
                <!--});-->
            <!--},-->
        <!--},-->
        <!--mounted: function () {-->
            <!--this.getSession();-->
<!--//            if (!this.shared.session.exercises) {-->
<!--//                this.getSession();-->
<!--//            }-->
        <!--}-->
    <!--}-->
<!--</script>-->

<!--<style lang="scss" type="text/scss">-->
    <!--#session-page {-->
        <!--th {-->
            <!--text-align:center;-->
        <!--}-->
    <!--}-->
<!--</style>-->