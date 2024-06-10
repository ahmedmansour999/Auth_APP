@extends('pages.global_chat.index')

@section('title', 'Home')

@section('style', asset('asset/css/profile.css'))


@section('style_link')
    <link rel="stylesheet" href="{{ asset('asset/css/chat.css') }}">
@endsection

@section('chatbody')





    {{-- Head Of Chat Call And Video --}}
    <div class="py-2 px-4 border-bottom  d-block">
        <div class="d-flex align-items-center py-1">
            <div class="position-relative">
                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1" alt="Sharon Lessman"
                    width="40" height="40">
            </div>
            <div class="flex-grow-1 pl-3">
                <strong class="m-1" >{{ $to->name }}</strong>
                {{-- <div class="text-muted small"><em>Typing...</em></div> --}}
            </div>

        </div>
    </div>
    @if ($messages->count() > 0)
        <div class="position-relative">
            <div class="chat-messages p-4">
                @foreach ($messages as $message)
                    {{-- Message Body --}}
                    @if ($message->sender_id == Auth::id())

                        <div class="chat-message-left pb-4">
                            <div>
                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1"
                                    alt="Your Avatar" width="40" height="40">
                                <div class="text-muted small text-nowrap mt-2">{{ $message->date->format('g:i A') }}
                                </div>
                            </div>
                            <div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3 ">
                                <div class="font-weight-bold mb-1">You</div>
                                {{ $message->message }}
                            </div>
                        </div>

                    @else

                        <div class="chat-message-right pb-4" id="reciverMessage">
                            <div >
                                <img src="https://bootdey.com/img/Content/avatar/avatar3.png" class="rounded-circle mr-1"
                                    alt="Other User's Avatar" width="40" height="40">
                                <div class="text-muted small text-nowrap mt-2"  >{{ $message->date->format('g:i A') }}
                                </div>
                            </div>
                            <div class="flex-shrink-1 bg-white rounded py-2 px-3 ml-3 ">
                                <div class="font-weight-bold mb-1"  >{{ $message->receiver->name }}</div>
                                {{ $message->message }}
                            </div>
                        </div>

                    @endif
                @endforeach
            </div>
        </div>
    @else
        <div class="empty_chat">
            <p class="text-center">No messages yet.</p>
        </div>
    @endif

    {{-- Send Message Form --}}

    <div class="flex-grow-0 py-3 px-4 border-top">
        <form class="input-group" action="{{ route('SendMessage') }}" method="post">
            @csrf
            @method('post')
            <input type="hidden" name="chat_id" value="{{ $chat_id }}">
            <input type="hidden" name="reciver_id" value="{{ $to->id }}">
            <input type="text" name="message" class="form-control" placeholder="Type your message">
            <button class="btn btn-primary" type="submit">Send</button>
        </form>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <span class="alert alert-danger messageAlert" role="alert">
                    <span>{{ $error }}</span>
                </span>
            @endforeach
        @endif
    </div>

@endsection
