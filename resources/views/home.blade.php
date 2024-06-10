@extends('layout.header')

@section('title', 'Home')

@section('style', asset('asset/css/profile.css'))

@section('content')

    @if (session('user'))
        <div class="nav_container">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">DB</a>
                    <div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="profile_image btn-group">
                        <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img width="100%" height="100%"
                                src="{{ asset('asset/media/image/pexels-simon-robben-55958-614810.jpg') }}" alt="">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ Auth::user()->notifications->count() }}
                            </span>
                        </button>

                        <div id="notification-container" class="dropdown-menu dropdown-menu-end" role="menu"
                            aria-labelledby="drop3">
                            <section class="panel">
                                <header class="panel-heading">
                                    <strong>Notifications</strong>
                                </header>
                                <div id="notification-list" class="list-group list-group-alt">
                                    @if (Auth::user()->notifications->count() > 0)
                                        @foreach (Auth::user()->notifications as $notification)
                                            <a class="list-group-item">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <span class="badge bg-success">New</span>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="media-heading">{{ $notification->data['user_name'] }}
                                                        </h6>
                                                        <p>{{ $notification->data['message'] }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                                <footer class="panel-footer">
                                    <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                                    <a href="#notes" data-toggle="class:show animated fadeInRight">See all the
                                        notifications</a>
                                </footer>
                            </section>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    @else
        <!-- Display something if user is not logged in -->
    @endif


    @yield('page')

@endsection
