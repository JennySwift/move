<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#2196f3">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <title>Move</title>
    @include('shared.head-links')
</head>
<body>
<div id="app">
    <f7-statusbar></f7-statusbar>

    {{--<f7-panel right>--}}
        {{--<f7-list contacts-list>--}}
            {{--<f7-list-group>--}}
                {{--<f7-list-item class="panel-close" link="/sessions" title="Sessions"></f7-list-item>--}}
                {{--<f7-list-item class="panel-close" link="/workouts" title="Workouts"></f7-list-item>--}}
                {{--<f7-list-item class="panel-close" link="/exercises/" title="Exercises"></f7-list-item>--}}
            {{--</f7-list-group>--}}
        {{--</f7-list>--}}
    {{--</f7-panel>--}}

    {{--<div class="panel panel-right panel-cover">--}}
        {{--<div class="block">--}}
            {{--<f7-list-group>--}}
                {{--<f7-list-item class="panel-close" link="/sessions" title="Sessions"></f7-list-item>--}}
                {{--<f7-list-item class="panel-close" link="/workouts" title="Workouts"></f7-list-item>--}}
                {{--<f7-list-item class="panel-close" link="/exercises/" title="Exercises"></f7-list-item>--}}
            {{--</f7-list-group>--}}
        {{--</div>--}}
    {{--</div>--}}

    <f7-view id="main-view" main>

    </f7-view>
</div>

{{--<template id="home-page">--}}
    {{--<f7-page>--}}
        {{--<f7-navbar>--}}
            {{--<f7-nav-title>Move</f7-nav-title>--}}
            {{--<f7-nav-right>--}}
                {{--<f7-link icon-if-ios="f7:menu" icon-if-md="material:menu" panel-open="right"></f7-link>--}}
            {{--</f7-nav-right>--}}
        {{--</f7-navbar>--}}

        {{--<f7-list contacts-list>--}}
            {{--<f7-list-group>--}}
                {{--<f7-list-item link="/sessions" title="Sessions"></f7-list-item>--}}
                {{--<f7-list-item link="/workouts" title="Workouts"></f7-list-item>--}}
                {{--<f7-list-item link="/exercises/" title="Exercises"></f7-list-item>--}}
            {{--</f7-list-group>--}}
        {{--</f7-list>--}}

    {{--</f7-page>--}}
{{--</template>--}}

{{--@include('shared.footer.footer')--}}
@include('shared.scripts')
</body>
</html>
