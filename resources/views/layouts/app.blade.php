<!DOCTYPE html>
<html lang="en">

<head>
    @include('headerFooter.header')
    <!-- Include Header -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('css/evaluation-form.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="Sat, 01 Jan 2000 00:00:00 GMT">
</head>

<body class="@yield('body-class') layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Navbar -->
        {{-- <nav class="app-header navbar navbar-expand bg-body">
            <div class="container">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="d-none d-md-inline">{{ auth()->user()->fname ?? '' }}
                                {{ auth()->user()->lname ?? '' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="{{ asset('/images/Ellipse 2.png') }}" class="rounded-circle shadow"
                                    alt="User Image">
                                <p>
                                    {{ auth()->user()->fname ?? '' }} {{ auth()->user()->lname ?? '' }}
                                    <small>{{ auth()->user()->role ?? 'User' }}</small>
                                </p>
                            </li>
                            <li class="user-footer">
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav> --}}

        <!-- Sidebar -->
        @include('headerFooter.sidebar')
        <title>@yield('title', 'Default Title')</title>

        <!-- Breadcrumb -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container">

                    <div class="d-flex app-main__div">
                        <ol class="breadcrumb float-start" style="--bs-breadcrumb-divider: '>';" >
                            <li class="breadcrumb-item">
                                <u>{{ isset($title) ? trim(explode('|', $title)[1]) : 'Dashboard' }}</u>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                @yield('breadcrumb', 'Dashboard')
                            </li>
                        </ol>
                    </div>
                    
                </div>
            </div>
            <!-- Page Content -->
            <div class="container">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        @include('headerFooter.footer')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     // Check if user is logged out (no session user_email)
    //     var isGuest = {
    //         {
    //             session() - > has('user_email') ? 'false' : 'true'
    //         }
    //     };

    //     if (isGuest) {
    //         // Prevent back navigation by pushing a new state into the browser history
    //         history.pushState(null, null, location.href);
    //         window.onpopstate = function() {
    //             // Keep the user from navigating backward by pushing a new state
    //             history.pushState(null, null, location.href);
    //         };

    //         // Force hard refresh and strict redirect to "/"
    //         window.onpageshow = function(event) {
    //             if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
    //                 // If the page was loaded from cache, redirect strictly to "/"
    //                 window.location.href = '/'; // This ensures the user is strictly sent to the home page
    //             } else {
    //                 // Force a hard refresh and strictly redirect to "/"
    //                 window.location.replace('/'); // This avoids allowing navigation to the current page
    //             }
    //         };
    //     }
    // });



    document.addEventListener("DOMContentLoaded", function () {
        // Blade will output true/false directly into the JS
        var isGuest = {{ session()->has('user_email') ? 'false' : 'true' }};

        if (isGuest) {
            // Prevent back navigation
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.pushState(null, null, location.href);
            };

            // Redirect user to home page
            window.onpageshow = function (event) {
                if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                    window.location.href = '/';
                } else {
                    window.location.replace('/');
                }
            };
        }
    });
</script>

</html>