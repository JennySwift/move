<template>
    <td class="checkbox-cell">
        <f7-checkbox :checked="row.complete > 0" @change="row.complete = $event.target.checked"></f7-checkbox>
    </td>
</template>

<script>
    export default {
        props: [
            'row'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>