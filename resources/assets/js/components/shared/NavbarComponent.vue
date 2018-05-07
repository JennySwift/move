<template>
    <ul id="navbar" style="z-index:1000">

        <li class="big-screens"><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

        <li class="dropdown">
            <a href="#" data-toggle="dropdown">
                <span class="fa fa-heart"></span>
            </a>
            <a href="#" data-toggle="dropdown">
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><router-link to="/entries">Entries</router-link></li>
                <li><router-link to="/exercises">Exercises</router-link></li>
                <!--<li><router-link to="/units">Units</router-link></li>-->
                <!--<li><router-link to="/new-unit">New Unit</router-link></li>-->
                <li><router-link to="/series/#/add">New Series</router-link></li>
                <li><router-link to="/series">Series</router-link></li>
                <li><router-link to="/entries/#/add">New Entry</router-link></li>
            </ul>
        </li>

        <li>
            <a v-on:click="showFilters()" href="#" class="fa fa-search"></a>
        </li>

        <!--User-->
        <li class="dropdown gravatar-li">
            <a href="#" data-toggle="dropdown">
                You
            </a>
            <a href="#" data-toggle="dropdown" class="gravatar-container">
                <img v-bind:img="shared.me.gravatar" class="gravatar"/>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <!-- if they are a guest they shouldn't see this page anyway, but so that my code will work... -->
                <li><a href="/logout">Logout</a></li>
            </ul>
        </li>

    </ul>
</template>

<script>
//    import 'bootstrap'
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {},
        methods: {
            /**
             *
             */
            showFilters: function () {
                store.set(true, 'showFilters')
            }
        },
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../sass/shared/index';
    #navbar {
        //    display: none;
        display:flex;
        justify-content: space-around;
        position: fixed;
        top: 0;
        width: 100%;
        color: #888;
        @include gradient;
        padding: 0;
        margin: 0;
        @media (max-width: 320px) {
            .big-screens {
                display: none;
            }
        }
        .dropdown-menu {
            min-width: inherit;
            a {
                padding: 0 8px;
                color: $base1;
            }
        }
        > li {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: $navHeight;
            //min-width: 70px;
            > a {
                transition: all .5s ease;
            }
            &:hover {
                > a {
                    //font-size: 25px;
                }
            }
            .fa-heart {
                //color: red;
            }
        }
        a {
            color: white;
        }
        .gravatar-li {
            //min-width: 160px;
            .gravatar-container {
                margin-left: 5px;
                display: flex;
                align-items: center;
                .gravatar {
                    border-radius: 8px;
                    height: 32px;
                }
            }
        }
    }
</style>

