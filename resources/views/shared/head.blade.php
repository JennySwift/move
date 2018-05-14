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


<link href='https://fonts.googleapis.com/css?family=Annie+Use+Your+Telescope' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Gothic+A1|Lato:300|Montserrat" rel="stylesheet">
<link rel="stylesheet" href="/css/fontawesome-all.min.css">
<link rel="stylesheet" href="/css/framework7.min.css">
{{--<link rel="stylesheet" href="/css/framework7-icons.css">--}}

<link rel="stylesheet" href="{{ mix('/css/app.css') }}">