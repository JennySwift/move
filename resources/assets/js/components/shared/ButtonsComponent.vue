<template>
    <div class="buttons-container">
        <router-link
            v-if="redirectTo"
            :to="redirectTo"
            tag="button"
            class="btn btn-default"
        >
            Cancel
        </router-link>

        <button
            v-if="destroy"
            v-on:click="destroy()"
            class="btn btn-danger"
        >
            Delete
        </button>

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
    .buttons-container {
        display: flex;
        > * {
            flex-grow: 1;
            margin: 0 5px;
            &:first-child {
                margin-left: 0px;
            }
            &:last-child {
                margin-right: 0px;
            }
        }
    }
</style>