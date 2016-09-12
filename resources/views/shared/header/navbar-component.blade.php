<script id="navbar-template" type="x-template">

    <ul id="navbar" style="z-index:1000">

        <li class="big-screens"><a href="http://jennyswiftcreations.com">jennyswiftcreations</a></li>

        @if (Auth::guest())
            <li>
                <a href="/auth/login">Login</a>
            </li>
            <li>
                <a href="/auth/register">Register</a>
            </li>

            @else

            <li><a v-link="{path: '/'}" href="#" class="fa fa-home"></a></li>

            <li id="menu-dropdown" class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-heart"></span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a v-link="{path: '/exercises'}" href="#">Exercises</a></li>
                    <li><a v-link="{path: '/exercise-units'}" href="#">Units</a></li>
                </ul>
            </li>

            @include('shared.header.user')

        @endif

    </ul>

</script>




