<section class="banner light-blue-bg">
    <div class="slider-thumb"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-7 col-md-12 col-12">
                <div class="banner-content">
                    <h2 data-aos="fade-right" data-aos-duration="1000">
                        {{ $data['hero_title'] }}
                    </h2>
                    <p data-aos="fade-up" data-aos-duration="1500">
                        {{ $data['hero_content'] }}
                    </p>
                    <div class="banner-anchors">
                        <a href="{{ $data['hero_button1_url'] }}" class="site-btn grad-btn mb-2" data-aos="fade-up"
                           target="{{ $data['hero_button1_target'] }}" data-aos-duration="2000"><i
                                class="anticon {{ $data['hero_button1_icon'] }}"></i>{{ $data['hero_button1_level'] }}
                        </a>
                        <a href="{{ $data['hero_button2_url'] }}" class="site-btn white-btn" data-aos="fade-up"
                           target="{{ $data['hero_button2_target'] }}" data-aos-duration="2500"><i
                                class="anticon {{ $data['hero_button2_icon'] }}"></i>{{ $data['hero_button2_lavel'] }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                <div class="banner-right">
                    <img src="{{ asset($data['hero_right_img']) }}" alt="" class="banner-img" data-aos="fade-left"
                         data-aos-duration="2000"/>
                    <div class="dots" style="background: url({{ asset($data['hero_right_top_img']) }}) repeat;"
                         data-aos="fade-down-left" data-aos-duration="1500"></div>
                </div>
            </div>
        </div>
    </div>
</section>
