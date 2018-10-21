<template>
    <f7-app :params="f7params">
        <f7-statusbar></f7-statusbar>

        <f7-panel right cover>
            <f7-block>
                <f7-list contacts-list>
                    <f7-list-group>
                        <f7-list-item class="panel-close" link="/sessions" title="Sessions"></f7-list-item>
                        <f7-list-item class="panel-close" link="/workouts" title="Workouts"></f7-list-item>
                        <f7-list-item class="panel-close" link="/exercises/" title="Exercises"></f7-list-item>
                    </f7-list-group>
                </f7-list>
            </f7-block>

            <f7-block>
                <f7-list contacts-list>
                    <f7-list-group>
                        <f7-list-item class="panel-close" external link="https://jennyswiftcreations.com/privacy-policy" title="Privacy"></f7-list-item>
                        <f7-list-item class="panel-close" external link="https://jennyswiftcreations.com/credits" title="Credits"></f7-list-item>
                        <f7-list-item class="panel-close" external link="https://jennyswiftcreations.com" title="jennyswiftcreations"></f7-list-item>
                    </f7-list-group>
                </f7-list>
            </f7-block>

        </f7-panel>

        <f7-view id="main-view" main>

        </f7-view>
    </f7-app>
</template>

<script>
    import routes from './routes'
    export default {
        data: function () {
            return {
                f7params: {
                    root: '#app',
                    id: 'move-app',
                    name: 'Move',
                    theme: 'ios',
                    routes: routes,
                    view: {
                        pushState: true,
                        // stackPages: true,
                        // history: true,
                        // reloadAll: false,
                        // clearPreviousHistory: false,
                    },
                    panel: {
                        swipe: 'right'
                    },
                    smartSelect: {
                        pageTitle: 'Select Option',
                        openIn: 'popup',
                        searchbar: true,
                        closeOnSelect: true
                    }
                }
            }
        },
        mounted: function () {
            //Doing timeout so that I don't get an "app is not defined" error
            setTimeout(function () {
                store.getExercises();
                store.getExerciseUnits();
                store.getWorkouts();
                store.getSessions({});
            }, 100);
        },
    }
</script>

<style lang="scss" type="text/scss">
    //@import '../../../../sass/shared/index';
</style>