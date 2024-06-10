@extends('home')

@section('title', 'Home')

@section('style', asset('asset/css/profile.css'))


@section('style_link')
    <link rel="stylesheet" href="{{ asset('asset/css/chat.css') }}">
@endsection

@section('page')

    <main class="content">
        <div class="container p-0">

            <h1 class="h3 mb-3">Messages</h1>

            <div class="card">
                <div class="row g-0">

                    {{-- List Of Chat Member --}}
                    <div class="col-12 col-lg-5 col-xl-3 border-right">
                        @if ($users->count() > 0)

                            @foreach ($users as $user)
                                <form action="{{ route('chat') }}" method="post">
                                    @csrf
                                    @method('post')
                                    <input type="hidden" value="{{ $user->id }}" name="reciver_id">
                                    <input type="hidden" value="{{ Auth::id() }}" name="sender_id">
                                    <button type="submit" class="list-group-item list-group-item-action border-0">
                                        <div class="d-flex align-items-start m-2">
                                            <img src="https://bootdey.com/img/Content/avatar/avatar5.png"
                                                class="rounded-circle mr-1" alt="{{ $user->name }}" width="40"
                                                height="40">
                                            <div class="flex-grow-1 ml-3">
                                                {{ $user->name }}
                                                <div class="small"><span class="fas fa-circle chat-online"></span> Online
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                                <hr class="d-block d-lg-none mt-1 mb-0  ">
                            @endforeach
                        @else
                            <span class="text-center">No Users Yet</span>
                        @endif
                    </div>

                    {{-- Chat Body --}}
                    <div class="col-12 col-lg-7 col-xl-9">
                        @yield('chatbody')
                    </div>


                </div>
            </div>
        </div>
    </main>

@endsection
