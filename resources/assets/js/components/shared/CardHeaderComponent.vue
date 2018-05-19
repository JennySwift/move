<template>
    <div class="card-header">
        <div class="data-table-title">{{title}}</div>
        <div class="data-table-actions">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'title'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>