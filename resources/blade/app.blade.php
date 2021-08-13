<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    {{-- <link rel="apple-touch-icon" sizes="180x180" href="{{mix('/assets/images/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{mix('/assets/images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{mix('/assets/images/favicon-16x16.png')}}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,700;0,800;1,700;1,800&family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&display=swap"

    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,700;0,800;1,700;1,800&family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&display=swap" rel="stylesheet">
    </noscript> --}}

    @production
        <link href="{{ mix('/assets/css/app.min.css') }}" rel="stylesheet">
    @endproduction

    @env('local')
        <link href="{{ mix('/assets/css/app.css') }}" rel="stylesheet">
    @endenv

    @if (isset($withStripe) && $withStripe === true)
        <script src="//js.stripe.com/v3/"></script>
    @endif

    @routes

    <script src="/assets/js/manifest.js" defer></script>
    <script src="{{ mix('/assets/js/application-vendor.js') }}" defer></script>
    <script src="{{ mix('/assets/js/app.js') }}" defer></script>
</head>

<body>
    @inertia
</body>

</html>
