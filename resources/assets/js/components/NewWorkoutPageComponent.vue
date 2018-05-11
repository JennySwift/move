<template>
    <f7-page>
        <f7-navbar back-link="Back">
            <f7-nav-title>New Workout</f7-nav-title>
            <f7-nav-right>
                <f7-link icon-if-ios="f7:menu" icon-if-md="material:menu" panel-open="right"></f7-link>
            </f7-nav-right>
        </f7-navbar>

        <f7-list no-hairlines-md contacts-list inset>
            <f7-list-item>
                <f7-label>Enter a name for your workout</f7-label>
                <f7-input type="text" :value="newWorkout.name.name" @input="newWorkout.name = $event.target.value" clear-button=""></f7-input>
            </f7-list-item>

        </f7-list>

        <f7-block>
            <buttons
                :save="insertWorkout"
            >
            </buttons>
        </f7-block>


    </f7-page>

</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                baseUrl: '/api/workouts',
                newWorkout: {name: ''},
            }
        },
        methods: {
            /**
            *
            */
            insertWorkout: function () {
                var data = {
                    name: this.newWorkout.name
                };

                helpers.post({
                    url: this.baseUrl,
                    data: data,
                    array: 'workouts',
                    property: 'workout',
                    message: 'Workout created',
                    clearFields: this.clearFields,
                    callback: function (response) {
                        helpers.goToRoute('/workouts/' + response.id + '/edit');
                    }.bind(this)
                });
            }
        },
    }
</script>

<style lang="scss" type="text/scss">
    #new-workout-page {
        padding-top: 15px;
        label {
            text-align: center;
            width: 100%;
        }
        input {
            margin-bottom: 15px;
        }
    }
</style>