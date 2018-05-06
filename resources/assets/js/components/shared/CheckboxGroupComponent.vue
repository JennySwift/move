<template>
    <div class="checkbox-container">
        <input
            v-model="model"
            :id="id"
            type="checkbox"
        >
        <label :for="id">{{label}}</label>
    </div>
</template>

<script>
    export default {
        props: [
            'label',
            'model',
            'id'
        ]
    }
</script>

<style lang="scss" type="text/scss">

</style>