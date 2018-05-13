<template>
    <f7-popup class="history-popup">

        <f7-page :page-content="false">

            <p><a class="link popup-close" href="#">Close popup</a></p>

            <f7-page-content>
                <div class="data-table data-table-init card" v-for="session in shared.history.data">
                    <!-- Card Header -->
                    <div class="card-header">
                        <!-- Table title -->
                        <div class="data-table-title">{{session.name}} <small>{{session.created_at | dateFilter}}</small></div>
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

            <f7-toolbar>
                <f7-button v-bind:disabled="!shared.history.pagination.prev_page_url" v-on:click="prevPage()">Newer</f7-button>
                <f7-button v-on:click="toggleDateFormat()"><i class="far fa-clock"></i></f7-button>
                <f7-button v-bind:disabled="!shared.history.pagination.next_page_url" v-on:click="nextPage()">Older</f7-button>
            </f7-toolbar>


        </f7-page>
    </f7-popup>

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
        computed: {
            name: function () {
                if (this.shared.history.data[0]) {
                    return this.shared.history.data[0].exercises.data[0].name;
                }
            }
        },
        filters: {
            dateFilter: function (date) {
                return store.dateFilter(date);
            }
        },
        methods: {
            nextPage: function () {
                this.getHistory(this.shared.history.pagination.next_page_url);
            },

            prevPage: function () {
                this.getHistory(this.shared.history.pagination.prev_page_url);
            },
            isEmpty: function (obj) {
                return _.isEmpty(obj);
            },
            toggleDateFormat: function () {
                store.toggleDateFormat();
            },
            /**
             *
             */
            getHistory: function (url) {
                // var id = helpers.getIdFromRouteParams(this);
                var id = 1;

                if (!url) {
                    url = this.baseUrl + '/' + id + '?include=sessions';
                }
                else {
                    //This part isn't in the pagination url
                    url += '&include=sessions';
                }

                helpers.get({
                    url: url,
                    storeProperty: 'history',
                    pagination: true
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