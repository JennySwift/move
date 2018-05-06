<template>
    <div>
        <router-link v-if="redirectTo" :to="redirectTo">Cancel</router-link>

        <router-link v-if="destroy" v-on:click="destroy()" :to="redirectTo">Delete</router-link>

        <!--Not adding the redirectTo here because that's done with the JS in case there is an error and we don't want to redirect-->
        <button
            v-if="save"
            v-on:click="save()"
            class="btn btn-success"
        >
            Save
        </button>
    </div>
</template>

<script>
    export default {
        props: [
            'save',
            'destroy',
            'redirectTo'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>