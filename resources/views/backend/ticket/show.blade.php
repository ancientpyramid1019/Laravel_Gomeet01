@extends('backend.layouts.app')
@section('title')
    {{ __('Ticket Details') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Ticket Details') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card overflow-hidden">
                        <div class="site-card-header">
                            <h3 class="title"> {{ $ticket->title.' - '.$ticket->uuid }}

                                @if( $ticket->status == 'open')
                                    <span class="site-badge pending">{{ __('Open') }}</span>
                                @elseif($ticket->status == 'closed')
                                    <span class="site-badge primary-bg ">{{ __('Completed') }}</span>
                                @endif
                            </h3>
                            <div class="card-header-links">
                                @if( $ticket->status == 'open')
                                    <a href="{{ route('admin.ticket.close.now',$ticket->uuid) }}"
                                       class="card-header-link rounded-pill">{{ __('Close it') }}</a>
                                @endif
                            </div>
                        </div>
                        <div class="site-card-body">
                            <div class="support-ticket-single-message user">
                                <div class="logo">
                                    @if( null != $ticket->user->avatar)
                                        <img class="avatar" src="{{ asset($ticket->user->avatar)}}" alt="" height="40"
                                             width="40">
                                    @else
                                        <span
                                            class="avatar-text">{{ $ticket->user->first_name[0] }} {{ $ticket->user->last_name[0] }}</span>
                                    @endif
                                </div>
                                <div class="message-body">
                                    {!! $ticket->message !!}
                                </div>
                                <div class="message-footer">
                                    <div class="name">{{ $ticket->user->username }}</div>
                                    <div class="email"><a href="mailto:">{{ $ticket->user->email }}</a></div>
                                </div>
                                <div class="message-attachments">
                                    <div class="title">{{ __('Attachments') }}</div>
                                    <div class="single-attachment">

                                        <div class="attach">
                                            <a href="{{ asset($ticket->attach) }}" target="_blank"><i
                                                    class="anticon anticon-picture"></i>{{ substr($ticket->attach,14) }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach($ticket->messages as $message )
                                <div
                                    class="support-ticket-single-message @if($message->model == 'admin') admin @else user @endif">
                                    <div class="logo">
                                        @if( $message->model != 'admin')
                                            <img class="avatar" src="{{ asset($ticket->user->avatar ?? 'global/materials/user.png')}}" alt=""
                                                 height="40" width="40">
                                        @else
                                            <img class="avatar" src="{{ asset($message->user->avatar ?? 'global/materials/user.png')}}" alt=""
                                                 height="40" width="40">
                                        @endif
                                    </div>
                                    <div class="message-body">
                                        <div class="article">
                                            {!! $message->message !!}
                                        </div>
                                    </div>
                                    <div class="message-footer">
                                        @if( $message->model != 'admin')
                                        <div class="name">{{ $ticket->user->username }}</div>
                                        <div class="email"><a href="mailto:">{{ $ticket->user->email }}</a></div>
                                        @else
                                        <div class="name">{{ $message->user->name }}</div>
                                        @endif
                                    </div>
                                    @if($message->attach)
                                        <div class="message-attachments">
                                            <div class="title">{{ __('Attachments') }}</div>
                                            <div class="single-attachment">
                                                <div class="attach">
                                                    <a href="{{ asset($message->attach) }}" target="_blank"><i
                                                            class="anticon anticon-picture"></i>{{ substr($message->attach,14) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="site-card">

                        <div class="site-card-body">
                            <div class="progress-steps-form">
                                <form action="{{ route('admin.ticket.reply') }}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="uuid" value="{{ $ticket->uuid }}">
                                    <div class="row mb-3">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <div class="wrap-custom-file">
                                                <input
                                                    type="file"
                                                    name="attach"
                                                    id="attach"
                                                    accept=".gif, .jpg, .png"
                                                />
                                                <label for="attach">
                                                    <img
                                                        class="upload-icon"
                                                        src="{{ asset('admin-assets/images/avatar/upload.svg') }}"
                                                        alt=""
                                                    />
                                                    <span>{{ __('Attach Image') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="site-input-groups">
                                                <textarea class="form-textarea" placeholder="Write Replay"
                                                          name="message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="buttons">
                                        <button type="submit" class="site-btn blue-btn">
                                            {{ __('Submit') }}<i class="anticon anticon-double-right"></i>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

