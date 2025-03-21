<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('clients/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('clients/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('clients/css/style.css') }}" rel="stylesheet">

    <title>Nội thất Poly</title>
</head>

<body>
    @include('client.blocks.header')

    @include('client.blocks.banner')

    <div class="container">
        <div class="content">
            @yield('content')
        </div>
    </div>

    @include('client.blocks.footer')



    <script src="{{ asset('clients/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('clients/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('clients/js/custom.js') }}"></script>


</body>

</html>
