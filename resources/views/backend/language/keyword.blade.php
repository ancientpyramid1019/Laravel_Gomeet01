@extends('backend.layouts.app')
@section('title')
    {{ __('Language Keywords') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Language Keywords') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-table table-responsive">
                        <form action="{{ route('admin.language-keyword', ['language' => $language]) }}" method="get">
                            <div class="table-filter">
                                <div class="filter">
                                    <div class="search">
                                        <label for="">{{ __('Search:') }}</label>
                                        <input type="text" name="filter" value="{{ Request::get('filter') }}">
                                    </div>
                                </div>
                                <div class="filter d-flex">
                                    @include('backend.language.include.select', ['name' => 'language', 'items' => $languages, 'submit' => true, 'selected' => $language])
                                    @include('backend.language.include.select', ['name' => 'group', 'items' => $groups, 'submit' => true, 'selected' => Request::get('group'), 'optional' => true])
                                </div>
                            </div>
                        </form>
                        @if(count($translations))

                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">{{ __('GROUP / SINGLE') }}</th>
                                    <th scope="col">{{ __('KEY') }}</th>
                                    <th scope="col">en</th>
                                    <th scope="col">{{$language}}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($translations as $type => $items)

                                    @foreach($items as $group => $translations)

                                        @foreach($translations as $key => $value)

                                            @if(!is_array($value['en']))
                                                <tr>
                                                    <td>{{ $group }}</td>
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $value['en'] }}</td>
                                                    <td>
                                                        {{ $value[$language] }}
                                                    </td>
                                                    <td>
                                                        <button class="round-icon-btn primary-btn edit-language-keyword"
                                                                data-language="{{ $language }}"
                                                                data-group="{{ $group }}" data-key="{{ $key }}"
                                                                data-value="{{ $value[$language] }}"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Edit Language"><i
                                                                icon-name="edit-3"></i></button>
                                                    </td>
                                                </tr>
                                            @endif

                                        @endforeach

                                    @endforeach

                                @endforeach


                                </tbody>
                            </table>

                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Edit Language Key-->
        <div class="modal fade" id="editKeyword" tabindex="-1" aria-labelledby="editLanguageKeyModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content site-table-modal">
                    <div class="modal-body popup-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <form action="{{ route('admin.language-keyword-update') }}" method="post">
                            @csrf
                            <div class="popup-body-text">
                                <h3 class="title">{{ __('Edit Keyword') }}</h3>
                                <div class="site-input-groups mb-2">
                                    <label class="box-input-label key-label"></label>
                                    <input type="hidden" class="box-input key-key" name="key">
                                    <input type="text" class="box-input key-value" name="value">
                                    <input type="hidden" class="box-input key-group" name="group">
                                    <input type="hidden" class="box-input key-language" name="language">
                                </div>
                                <div class="action-btns">
                                    <button type="submit" class="site-btn-sm primary-btn me-2">
                                        <i icon-name="check"></i>
                                        {{ __('Save Changes') }}
                                    </button>
                                    <a href="" class="site-btn-sm red-btn" type="button" data-bs-dismiss="modal"
                                       aria-label="Close"><i icon-name="x"></i>Close</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for Edit Language Key End-->


    </div>
@endsection
@section('script')

    <script>
        (function ($) {
            "use strict";

            $('.edit-language-keyword').on('click',function (e) {

                var key = $(this).data('key');
                var value = $(this).data('value');
                var group = $(this).data('group');
                var language = $(this).data('language');


                $('.key-label').html(key);
                $('.key-key').val(key);
                $('.key-value').val(value);
                $('.key-group').val(group);
                $('.key-language').val(language);

                $('#editKeyword').modal('toggle')
            })


        })(jQuery);
    </script>
@endsection
