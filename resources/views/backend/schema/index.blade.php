@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Schema') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All Schemas') }}</h2>
                            @can('schema-create')
                                <a href="{{route('admin.schema.create')}}" class="title-btn"><i
                                        icon-name="plus-circle"></i>{{ __('Add New') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <div class="site-table table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Icon') }}</th>
                                        <th scope="col">{{ __('Plan Name') }}</th>
                                        <th scope="col">{{ __('Amount') }}</th>
                                        <th scope="col">{{ __('Badge') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($schemas as $schema)
                                        <tr>
                                            <td>
                                                <img
                                                    class="avatar"
                                                    src="{{ asset($schema->icon) }}"
                                                    alt=""
                                                />
                                            </td>
                                            <td><strong>{{$schema->name}}</strong></td>
                                            <td>
                                                <strong>{{ $schema->type == 'range'? $schema->min_amount.' '.$currency.'-'.$schema->max_amount.' '.$currency:$schema->fixed_amount.' '.$currency }}</strong>
                                            </td>
                                            <td>
                                                <div @class([
                                                    'site-badge', // common classes
                                                    'success' => $schema->featured,
                                                    'pending' => !$schema->featured
                                                  ])>{{ $schema->featured ? $schema->badge : 'No Feature Badge' }}</div>
                                            </td>
                                            <td>
                                                <div @class([
                                                    'site-badge', // common classes
                                                    'success' => $schema->status,
                                                    'danger' => !$schema->status
                                                  ])>{{ $schema->status ? 'Active' : 'Deactivated' }}</div>
                                            </td>
                                            <td>
                                                @can('schema-edit')
                                                    <a href="{{route('admin.schema.edit',$schema->id)}}"
                                                       class="round-icon-btn primary-btn">
                                                        <i icon-name="edit-3"></i>
                                                    </a>
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
