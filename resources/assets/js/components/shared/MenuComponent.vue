<template>
    <f7-popover id="workouts-menu">
        <f7-list contacts-list>
            <f7-list-group>
                <f7-list-item class="panel-close" link="/sessions" title="Sessions" popover-close></f7-list-item>
                <f7-list-item class="panel-close" link="/workouts" title="Workouts" popover-close></f7-list-item>
                <f7-list-item class="panel-close" link="/exercises/" title="Exercises" popover-close></f7-list-item>
                <slot></slot>
            </f7-list-group>
        </f7-list>
    </f7-popover>

</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        methods: {

        }
    }

</script>

