<section class="cta site-overlay"
         style="background: url({{ asset($data['cta_bg_img']) }}) no-repeat center center fixed;">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-12">
                <div class="headding" data-aos="fade-right" data-aos-duration="1500">
                    <h2 class="white-color">
                        {{ $data['cta_title'] }}
                    </h2>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12">
                <div class="btns" data-aos="fade-left" data-aos-duration="2000">
                    <a href="{{ $data['cta_button1_url'] }}" target="{{ $data['cta_button1_target'] }}"
                       class="site-btn primary-btn"><i
                            class="anticon {{ $data['cta_button1_icon'] }}"></i>{{ $data['cta_button1_level'] }}</a>
                    <a href="{{ $data['cta_button2_url'] }}" target="{{ $data['cta_button2_target'] }}"
                       class="site-btn"><i
                            class="anticon {{ $data['cta_button2_icon'] }}"></i>{{ $data['cta_button2_lavel'] }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
