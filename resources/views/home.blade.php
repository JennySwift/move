<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
    <navbar></navbar>
    <feedback></feedback>
    <loading></loading>


    <div class="container">
        <router-view></router-view>
    </div>
</div>

@include('shared.footer.footer')
@include('shared.scripts')

</body>
</html>
