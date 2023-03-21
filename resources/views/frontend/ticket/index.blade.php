@extends('frontend.layouts.user')
@section('title')
    {{ __('Support Tickets') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Support Tickets') }}</h3>
                    <div class="card-header-links">
                        <a href="{{ route('user.ticket.new') }}" class="card-header-link">{{ __('Create Ticket') }}</a>
                    </div>
                </div>
                <div class="site-card-body">
                    <div class="site-transactions">
                        @foreach($tickets as $ticket)
                            <div class="single">
                                <div class="left">
                                    <div class="icon">
                                        <i icon-name="flag"></i>
                                    </div>
                                    <div class="content">
                                        <div class="title">{{ $ticket->title }}</div>
                                        <div class="date">{{ __('Created ').$ticket->created_at }}
                                            @if($ticket->isOpen())
                                                <span
                                                    class="ms-2 status site-badge badge-pending">{{ __('Opened') }}</span>
                                            @elseif($ticket->isClosed())
                                                <span
                                                    class="ms-2 status site-badge badge-success">{{ __('Completed') }}</span>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="right">
                                    <div class="action">

                                        @if($ticket->isOpen())
                                            <a href="{{ route('user.ticket.close.now',$ticket->uuid) }}" class="cancel"
                                               data-bs-toggle="tooltip" title="Complete Ticket"
                                               data-bs-original-title="Complete Ticket"><i icon-name="check"></i></a>
                                            <a href="{{ route('user.ticket.show',$ticket->uuid) }}"
                                               data-bs-toggle="tooltip" title="Show Ticket"
                                               data-bs-original-title="Show Ticket"><i icon-name="eye"></i></a>
                                        @elseif($ticket->isClosed())
                                            <a href="#" class="cancel disabled"><i icon-name="check"></i></a>
                                            <a href="{{ route('user.ticket.show',$ticket->uuid) }}"
                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                               title="Re-open the Ticket"><i icon-name="book-open"></i></a>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($tickets->isEmpty())
                            <p class="centered">{{ __('No Data Found') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
