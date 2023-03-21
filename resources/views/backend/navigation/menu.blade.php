@extends('backend.navigation.index')
@section('navigation_content')

    <div class="col-xl-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('All Menu Items') }}</h3>
                <div class="card-header-links">
                    <a href="" class="card-header-link" type="button" data-bs-toggle="modal"
                       data-bs-target="#addNewNavMenu">{{ __('Add New') }}</a>
                </div>
            </div>
            <div class="site-card-body">
                <div class="site-table table-responsive mb-0">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('Menu Item Name') }}</th>
                            <th scope="col">{{ __('Menu URL') }}</th>
                            <th scope="col">{{ __('Page') }}</th>
                            <th scope="col">{{ __('Display In') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($navigations as $navigation)
                            <tr>
                                <td>
                                    <strong>{{ $navigation->name }}</strong>
                                </td>
                                <td>{{ $navigation->url }}</td>
                                <td><strong
                                        class="site-badge primary">{{ $navigation?->page->title ?? 'Custom Url' }}</strong>
                                </td>
                                <td><strong>{{ $navigation->type }}</strong></td>

                                <td>
                                    @if($navigation->status)
                                        <div class="site-badge success" )>{{ __('Active') }}</div>
                                    @else
                                        <div class="site-badge pending" )>{{ __('DeActive') }}</div>
                                    @endif
                                </td>

                                <td>
                                    <button class="round-icon-btn primary-btn editNavMenu"
                                            data-id="{{ $navigation->id }}" type="button">
                                        <i icon-name="edit-3"></i>
                                    </button>
                                    <button class="round-icon-btn red-btn deleteMainMenuItem" type="button"
                                            data-id="{{ $navigation->id }}">
                                        <i icon-name="trash-2"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Nav Menu -->
    @include('backend.navigation.include.__add_new')
    <!-- Modal for Add New Nav Menu-->

    <!-- Modal for Add New Nav Menu -->
    @include('backend.navigation.include.__edit')
    <!-- Modal for Add New Nav Menu-->

    <!-- Modal for Delete Menu -->
    @include('backend.navigation.include.__delete')
    <!-- Modal for Delete Menu End-->

@endsection
@section('script')
    <script>
        $('#page-select').on('change',function (e) {
            "use strict"
            var page = $(this).val();
            $('.custom-url-input').addClass('hidden')
            if (page === 'custom') {
                $('.custom-url-input').removeClass('hidden')
            }
        })

        $('.editNavMenu').on('click',function (e) {
            "use strict"
            var id = $(this).data('id');
            $('.edit-section').empty();
            $.get('menu-edit/' + id, function ($data) {
                $('.edit-section').append($data)
                $('#editNavMenu').modal('show');

            })

        })


        $('body').on('change', '.edit-page-select', function (e) {
            e.preventDefault();
            "use strict"
            var page = $(this).val();
            $('.edit-custom-url-input').addClass('hidden')
            if (page === 'custom') {
                $('.edit-custom-url-input').removeClass('hidden')
            }
        })


        $('.deleteMainMenuItem').on('click',function (e) {
            "use strict"
            var id = $(this).data('id');
            $('.menuId').val(id);
            $('#deleteMenu').modal('toggle')

        })
    </script>
@endsection
