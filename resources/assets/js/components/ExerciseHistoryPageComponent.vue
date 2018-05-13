<template>
    <div>
        <f7-page :page-content="false">
            <navbar :title="shared.exercise.name" popover-id="history">
            </navbar>


            <f7-page-content>
                <div class="data-table data-table-init card" v-for="session in shared.history.data">
                    <!-- Card Header -->
                    <div class="card-header">
                        <!-- Table title -->
                        <div class="data-table-title">{{session.name}} <small>{{session.created_at}}</small></div>
                    </div>
                    <!-- Card Content -->
                    <div class="card-content">
                        <table>
                            <thead>
                            <tr>
                                <th class="numeric-cell">LEVEL</th>
                                <th class="numeric-cell">{{session.exercises.data[0].unit.data.name}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="row in session.exercises.data">
                                <td class="numeric-cell">
                                    {{row.level}}
                                </td>
                                <td class="numeric-cell">
                                    {{row.quantity}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </f7-page-content>


        </f7-page>
    </div>


</template>

<script>
    import Vue from 'vue'
    import swal from 'sweetalert2'
    var object = require('lodash/object');
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/exercises'
            }
        },
        methods: {
            isEmpty: function (obj) {
                return _.isEmpty(obj);
            },
            /**
             *
             */
            getHistory: function () {
                var id = helpers.getIdFromRouteParams(this);

                helpers.get({
                    url: this.baseUrl + '/' + id + '?include=sessions',
                    storeProperty: 'history'
                });
            },
        },
        mounted: function () {
            this.getHistory();
        }
    }
</script>

<style lang="scss" type="text/scss">

</style>