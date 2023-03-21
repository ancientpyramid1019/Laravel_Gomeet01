<header class="header">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{route('home')}}"><img src="{{ asset(setting('site_logo','global')) }}"
                                                                  alt=""/></a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav m-auto mb-2 mb-lg-0 main-nav">
                    @foreach($navigations as $navigation)
                        @if($navigation->page->status|| $navigation->page_id == null)
                            <li class="nav-item">
                                <a class="nav-link @if(url($navigation->url) == Request::url() ) active @endif"
                                   href="{{ url($navigation->url) }}">{{ $navigation->name }}</a>
                            </li>
                        @endif
                    @endforeach

                </ul>
                <div class="header-right-btn">
                    @auth('web')
                        <a href="{{route('user.dashboard')}}" class="site-btn-sm grad-btn"><i
                                class="anticon anticon-dashboard"></i>{{ __('Dashboard') }}</a>

                    @else
                        <a href="{{route('register')}}" class="site-btn-sm primary-btn"><i
                                class="anticon anticon-user-add"></i>{{ __('Register') }}</a>
                        <a href="{{route('login')}}" class="site-btn-sm grad-btn ms-2"><i
                                class="anticon anticon-user"></i>{{ __('Account') }}</a>

                    @endauth

                    {{-- <div class="color-switcher">
                        <i icon-name="moon" class="dark-icon" data-mode="dark"></i>
                        <i icon-name="sun" class="light-icon" data-mode="light"></i>
                    </div> --}}
                </div>
            </div>
        </div>
    </nav>
</header>
@push('script')
    <script>
        // Color Switcher
        $(".color-switcher").on('click', function () {
            "use strict"
            $("body").toggleClass("dark-theme");
            var url = '{{ route("mode-theme") }}';
            $.get(url)
        });
    </script>
@endpush
