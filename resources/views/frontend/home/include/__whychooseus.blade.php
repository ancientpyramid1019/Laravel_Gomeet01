@php
    $landingContent =\App\Models\LandingContent::where('type','whychooseus')->get();
@endphp

<section class="why-choose-us section-style-2">
    <div class="bat-right" style="background: url({{ asset($data['right_img']) }}) repeat;" data-aos="fade-down-left"
         data-aos-duration="2000"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="why-choose-us-img" data-aos="fade-right" data-aos-duration="2000">
                    <img src="{{ asset($data['left_img']) }}" alt=""/>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="why-choose-us-content">
                    <div class="section-title">
                        <h4 data-aos="fade-down" data-aos-duration="1500">{{ $data['title_small'] }}</h4>
                        <h2 data-aos="fade-down" data-aos-duration="1000">{{ $data['title_big'] }}</h2>
                    </div>

                    @foreach($landingContent as $content)
                        <div class="single" data-aos="fade-down" data-aos-duration="2000">
                            <div class="icons">
                                <div class="icon">
                                    <i class="anticon {{ $content->icon }}"></i>
                                </div>
                            </div>
                            <div class="content">
                                <h4>{{ $content->title }}</h4>
                                <p>
                                    {{ $content->description }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
