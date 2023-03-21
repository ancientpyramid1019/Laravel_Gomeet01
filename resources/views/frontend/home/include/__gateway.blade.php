@php
    $gateways = \App\Models\Gateway::where('status',true)->pluck('logo','name');
@endphp

<section class="section-style-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-12">
                <div class="section-title text-center">
                    <h4 data-aos="fade-down" data-aos-duration="2000">{{ $data['title_small'] }}</h4>
                    <h2 data-aos="fade-down" data-aos-duration="1500">{{ $data['title_big'] }}</h2>
                </div>
            </div>
        </div>
        <div class="row brands-logo justify-content-center">
            @foreach($gateways as $name => $logo)
                <div class="col-xl-2 col-lg-2 col-md-2 col-6">
                    <div class="single-brands-logo" data-aos="fade-down" data-aos-duration="2000">
                        <img src="{{ asset($logo) }}" alt=""/>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>
