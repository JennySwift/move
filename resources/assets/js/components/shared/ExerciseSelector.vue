<template>
    <selector
        :options="shared.exercises"
        display-prop="name"
        :on-select="onSelect"
        field-to-filter-by="name"
        :id="id"
        popup-title="Add Exercise"
    >
    </selector>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state
            }
        },
        props: {
            id: {},
            onSelect: {}
        }
    }
</script>

<style lang="scss" type="text/scss">
    //@import '../../../../sass/shared/index';
</style>