<template>
    <f7-actions :id="exercise[0].exercise_id + '-session-actions'">
        <f7-actions-group>
            <f7-actions-button v-on:click="addSet(exercise[0])">Add Set</f7-actions-button>
            <f7-actions-button>
                <f7-link :href="'/exercises/' + exercise[0].exercise_id">View Exercise</f7-link>
            </f7-actions-button>
        </f7-actions-group>
    </f7-actions>
</template>

<script>
    export default {
        props: [
            'exercise',
            'addSet'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>