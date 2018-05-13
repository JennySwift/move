<template>
    <f7-page>
        <navbar title="Sessions" popover-id="sessions">
            <f7-list-item v-on:click="createSessionFromSavedWorkout()" title="Start Session" link="false" popover-close></f7-list-item>
        </navbar>

        <f7-list contacts-list>
            <f7-list-group>
                <!--v-bind:text="session.created_at | formatDate"-->
                <f7-list-item
                    v-for="session in shared.sessions.data"
                    v-bind:title="session.name"
                    v-bind:after="session.created_at | dateFilter"
                    :link="'/sessions/' + session.id"
                    v-on:click="setSession(session)"
                    v-bind:key="session.id"
                >
                </f7-list-item>
            </f7-list-group>

        </f7-list>

        <f7-toolbar>
            <f7-button v-bind:disabled="!shared.sessions.pagination.prev_page_url" v-on:click="prevPage()">Newer</f7-button>
            <f7-button v-on:click="toggleDateFormat()"><i class="far fa-clock"></i></f7-button>
            <f7-button v-bind:disabled="!shared.sessions.pagination.next_page_url" v-on:click="nextPage()">Older</f7-button>
        </f7-toolbar>

    </f7-page>

</template>

<script>
    import swal from 'sweetalert2'
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/sessions',
            };
        },
        filters: {
            formatDate: function (date) {
                return helpers.formatDateForUser(date);
            },
            daysAgo: function (date) {
                return helpers.getDaysAgo(date);
            },
            dateFilter: function (date) {
               return store.dateFilter(date);
            }
        },
//        computed: {
//            sortedSessions: function () {
//                console.log(_.groupBy(this.shared.sessions, 'created_at'));
//              return _.groupBy(this.shared.sessions, 'created_at');
//            }
//        },
        methods: {
            toggleDateFormat: function () {
               store.toggleDateFormat();
            },
            nextPage: function () {
                store.getSessions(this.shared.sessions.pagination.next_page_url);
            },

            prevPage: function () {
                store.getSessions(this.shared.sessions.pagination.prev_page_url);
            },

            /**
             *
             */
            setSession: function (session) {
                store.set(session, 'session');
            },

            getWorkoutOptions: function () {
                var options = {};
                _.forEach(this.shared.workouts, function (value, index) {
                    options[value.id] = value.name;
                });
                return options;
            },

            /**
            *
            */
            createSessionFromSavedWorkout: function () {
                var that = this;
                swal({
                    showCloseButton: true,
                    animation: false,
                    title: "Choose a Workout",
                    input: 'select',
                    inputOptions: this.getWorkoutOptions(),
                }).then(function (result) {
                    if (result.value) {
                        var data = {
                            workout_id: result.value
                        };

                        helpers.post({
                            url: that.baseUrl,
                            data: data,
//                            array: 'sessions',
                            message: 'Enjoy your workout :)',
                            clearFields: that.clearFields,
                            callback: function (response) {
                                helpers.goToRoute('/sessions/' + response.id);
                            }.bind(that)
                        });
                    }
                })

            }
        },
        mounted: function () {
            // store.getSessions();
        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../sass/shared/index';
    #activity-page {
        .list-group-item {
            margin: 15px 0;
        }
        .dates-container {
            display: flex;
            justify-content: space-between;
            font-family: $font3;
        }
        .new-btn {
            margin-top: 15px;
        }
        .pagination-btns {
            display: flex;
            > * {
                flex-grow: 1;
                margin: 0 4px;
                &:first-child {
                    margin-left: 0;
                }
                &:last-child {
                    margin-right: 0;
                }
            }
        }
    }
</style>

