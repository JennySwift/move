<template>
    <div id="activity-page">
        <div class="container">

            <router-link to="/" tag="button" class="new-btn btn btn-default">Start Workout</router-link>
<!--{{sortedSessions}}-->
            <div>
                <div class="list-group-div">
                    <div class="list-group-item-div-container" v-for="session in shared.sessions">
                        <div class="dates-container">
                            <span>{{session.created_at | formatDate}}</span>
                            <span>{{session.created_at | getDaysAgo}}</span>
                        </div>
                        <router-link

                            :to="'/sessions/' + session.id"
                            tag="div"
                            class="pointer list-group-item-div"
                            v-on:click="setSession(session)"
                            v-bind:key="session.id"
                        >
                            {{session.name}}
                        </router-link>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: 'api/sessions'
            };
        },
        filters: {
            formatDate: function (date) {
                return helpers.formatDateForUser(date);
            },
            getDaysAgo: function (date) {
                return helpers.getDaysAgo(date);
            }
        },
//        computed: {
//            sortedSessions: function () {
//                console.log(_.groupBy(this.shared.sessions, 'created_at'));
//              return _.groupBy(this.shared.sessions, 'created_at');
//            }
//        },
        methods: {

            /**
             *
             */
            setSession: function (session) {
                store.set(session, 'session');
            },
        },
        mounted: function () {
            store.getSessions();
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
    }
</style>

