@php
    $footerContent = json_decode(\App\Models\LandingPage::where('status',true)->where('code','footer')->first()->data,true);
@endphp

<footer class="footer dark-blue-bg section-style-2">
    <div class="bat-right" style="background: url({{ asset($footerContent['right_img']) }}) repeat;"
         data-aos="fade-down-left" data-aos-duration="2000"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <div class="footer-widget" data-aos="fade-down" data-aos-duration="1000">
                    <h4>{{ $footerContent['widget_left_title'] }}</h4>
                    <p>
                        {{ $footerContent['widget_left_description'] }}
                    </p>
                    <div class="socials">
                        @foreach(\App\Models\Social::all() as $social)
                            <a href="{{ url($social->url) }}"><i class="{{ $social->class_name }}"></i></a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12">
                <div class="row">

                    @foreach($navigations as $navigation)

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="footer-widget" data-aos="fade-down" data-aos-duration="1500">
                                <h4>{{ $footerContent['widget_title_'.$loop->iteration] }}</h4>
                                <div class="footer-nav">
                                    <ul>
                                        @foreach($navigation as $menu)
                                            @if($menu->page->status|| $menu->page_id == null)
                                                <li><a href="{{ url($menu->url) }}">{{ $menu->name }}</a></li>
                                            @endif
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>

                    @endforeach


                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="copyright-text" data-aos="fade-down" data-aos-duration="3000">
                    <p>
                        {{ $footerContent['copyright_text'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
