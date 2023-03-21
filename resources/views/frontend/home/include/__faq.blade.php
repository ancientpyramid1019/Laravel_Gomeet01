@php
    $landingContent =\App\Models\LandingContent::where('type','faq')->get();
@endphp
<section class="section-style">
    <div class="bat-left" style="background: url({{ asset($data['left_img']) }}) repeat;" data-aos="fade-down-right"
         data-aos-duration="2000"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="section-title centered">
                    <h4 data-aos="fade-down" data-aos-duration="2000">{{ $data['title_small'] }}</h4>
                    <h2 data-aos="fade-down" data-aos-duration="1500">{{ $data['title_big'] }}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-8 col-12">
                <div class="faq-contents">
                    <div class="accordion" id="accordionExample">
                        @foreach($landingContent as $content)
                            <div class="accordion-item" data-aos="fade-down" data-aos-duration="1000">
                                <h2 class="accordion-header" id="headingOne{{ $content->id }}">
                                    <button class="accordion-button @if($loop->iteration != 1) collapsed @endif"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne{{ $content->id }}" aria-expanded="true"
                                            aria-controls="collapseOne">
                                        <span class="icon"><i
                                                class="anticon anticon-check"></i></span>{{ $content->title }}
                                    </button>
                                </h2>
                                <div id="collapseOne{{ $content->id }}"
                                     class="accordion-collapse collapse  @if($loop->iteration == 1) show @endif"
                                     aria-labelledby="headingOne{{ $content->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="para">{{ $content->description }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
