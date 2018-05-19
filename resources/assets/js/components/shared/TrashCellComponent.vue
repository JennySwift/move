<template>
    <td class="actions-cell" v-on:click="removeSet(row)" v-show="deletingRows">
        <f7-icon f7="trash" size="22"></f7-icon>
    </td>
</template>

<script>
    export default {
        props: [
            'removeSet',
            'deletingRows',
            'row'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>