<!DOCTYPE html>
<html>
<head>
    @include('shared.head')
</head>
<body>
<div id="app">
    {{--<f7-statusbar></f7-statusbar>--}}

    {{--<f7-view id="main-view" main>--}}

    {{--</f7-view>--}}

    <div class="main">
        @section('content')
        @show
    </div>
</div>

{{--@include('shared.footer.footer')--}}
{{--@include('shared.scripts')--}}
</body>
</html>
