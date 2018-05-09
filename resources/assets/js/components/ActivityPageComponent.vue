<template>
    <div id="activity-page">
        <div class="container">

            <router-link to="/" tag="button" class="new-btn btn btn-default">Start Workout</router-link>

            <div>
                <ul class="list-group">
                    <router-link
                        v-for="session in shared.sessions"
                        :to="'/sessions/' + session.id"
                        tag="li"
                        class="list-group-item pointer"
                        v-on:click="setSession(session)"
                        v-bind:key="session.id"
                    >
                        {{session.name}}
                    </router-link>
                </ul>

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
    #exercises-page {
        td {
            text-align: left;
        }
        @media (max-width: 320px) {
            .big-screens {
                display: none;
            }
        }
        table {
            width: auto;
            margin: auto;
        }
        .btn-container {
            margin-bottom: 5px;
            display: flex;
            justify-content: flex-end;
        }
        #add-exercise-btn {
            margin-top: 17px;
        }
    }
</style>

