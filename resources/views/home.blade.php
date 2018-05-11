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

    {{--<f7-preloader style="width: 44px; height: 44px"></f7-preloader>--}}

    <!-- Left Panel -->
    <f7-panel left reveal theme-dark>
        <f7-view id="left-panel-view">
            <f7-page>
                <f7-navbar title="Left Panel"></f7-navbar>
                <f7-block strong>
                    <p>Left panel content goes here</p>
                    <p>Weeeeee</p>
                </f7-block>
            </f7-page>
        </f7-view>
    </f7-panel>

    <f7-view id="main-view" main>

    </f7-view>
</div>

<template id="home-page">
    <f7-page>
        <f7-navbar>
            <f7-nav-left>
                <f7-link icon-if-ios="f7:menu" icon-if-md="material:menu" panel-open="left"></f7-link>
            </f7-nav-left>
            <f7-nav-title>Move</f7-nav-title>
        </f7-navbar>

        <f7-list contacts-list>
            <f7-list-group>
                <f7-list-item link="/sessions" title="Sessions"></f7-list-item>
                <f7-list-item link="/workouts" title="Workouts"></f7-list-item>
                <f7-list-item link="/exercises/" title="Exercises"></f7-list-item>
            </f7-list-group>
        </f7-list>

    </f7-page>
</template>

{{--@include('shared.footer.footer')--}}
@include('shared.scripts')
</body>
</html>
