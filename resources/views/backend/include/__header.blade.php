<div class="header">
    <div class="logo">
        <a href="{{route('admin.dashboard')}}">
            <img
                class="logo-unfold"
                src="{{asset(setting('site_logo','global'))}}"
                alt="Logo"
            />
            <img
                class="logo-fold"
                src="{{asset(setting('site_logo','global'))}}"
                alt="Logo"
            />
        </a>
    </div>
    <div class="nav-wrap">
        <div class="nav-left">
            <button class="sidebar-toggle"><i icon-name="list"></i></button>
        </div>
        <div class="nav-right">
            <div class="single-nav-right">
                <a href="{{ route('home') }}" target="_blank" class="item" data-bs-toggle="tooltip" title=""
                   data-bs-placement="left" data-bs-original-title="Visit Landing Page">
                    <i icon-name="globe"></i>
                </a>
            </div>
            <div class="single-nav-right">
                <button type="button" class="item" data-bs-toggle="dropdown" aria-expanded="false">
                    <i icon-name="user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a href="{{ route('admin.profile') }}" class="dropdown-item"><i
                                icon-name="user"></i>{{ __('Profile') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.password-change') }}" class="dropdown-item">
                            <i icon-name="lock"></i>{{ __('Change Password') }}
                        </a>
                    </li>
                    <li class="logout">

                        <a href="{{ url('admin/logout') }}" class="dropdown-item" type="button"
                           onclick="event.preventDefault(); localStorage.clear();  $('#logout-form').submit();">
                            <i icon-name="log-out"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
