<section class="section-style-2 dark-blue-bg newslatter-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="newslatter" data-aos="fade-down" data-aos-duration="1500">
                    <form action="{{ route('subscriber') }}" method="post">
                        @csrf
                        <input type="email" name="email" placeholder="Email Address" required/>
                        <button type="submit">{{ __('Subscribe') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
