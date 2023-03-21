@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Schema') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Edit Schema') }}</h2>
                            <a href="{{ url()->previous() }}" class="title-btn"><i
                                    icon-name="corner-down-left"></i>{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-12 col-md-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <form action="{{route('admin.schema.update',$schema->id)}}" method="post"
                                  enctype="multipart/form-data" class="row">
                                @method('PUT')
                                @csrf
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Upload Icon:') }}</label>
                                                <div class="wrap-custom-file">
                                                    <input
                                                        type="file"
                                                        name="icon"
                                                        id="schema-icon"
                                                        accept=".gif, .jpg, .png"
                                                    />
                                                    <label for="schema-icon" class="file-ok"
                                                           style="background-image: url({{ asset($schema->icon) }})">
                                                        <img
                                                            class="upload-icon"
                                                            src="{{ asset('global/materials/upload.svg')}}"
                                                            alt=""
                                                        />
                                                        <span>{{ __('Update Avatar') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="schema-name @if($schema->featured) col-xl-6 @else col-xl-12 @endif">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Schema Name:') }}</label>
                                        <input
                                            type="text"
                                            name="name"
                                            value="{{$schema->name}}"
                                            class="box-input"
                                            placeholder="Investment Plan name"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6 schema-badge @if(!$schema->featured) hidden @endif">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Schema Badge:') }}</label>
                                        <input
                                            type="text"
                                            class="box-input"
                                            placeholder="Schema Badge"
                                            name="badge"
                                            value="{{$schema->badge}}"
                                        />
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Schema Type:') }}</label>
                                        <div class="switch-field same-type">
                                            <input
                                                type="radio"
                                                id="fixed-type"
                                                name="type"
                                                value="fixed"
                                                @if($schema->type == 'fixed') checked @endif
                                            />
                                            <label for="fixed-type">{{ __('Fixed') }}</label>
                                            <input
                                                type="radio"
                                                id="range-type"
                                                name="type"
                                                value="range"
                                                @if($schema->type == 'range') checked @endif
                                            />
                                            <label for="range-type">{{ __('Range') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups row">


                                        <div class="col-xl-6 min-amount @if($schema->type == 'fixed') hidden @endif">
                                            <label class="box-input-label" for="">{{ __('Min Amount:') }}</label>
                                            <div class="input-group joint-input">
                                                <input type="text" name="min_amount" value="{{ $schema->min_amount }}"
                                                       oninput="this.value = validateDouble(this.value)"
                                                       class="form-control" required/>
                                                <span
                                                    class="input-group-text">{{ setting('site_currency','global') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 max-amount @if($schema->type == 'fixed') hidden @endif">
                                            <label class="box-input-label" for="">{{ __('Max Amount:') }}</label>
                                            <div class="input-group joint-input">
                                                <input type="text" name="max_amount" value="{{ $schema->max_amount }}"
                                                       oninput="this.value = validateDouble(this.value)"
                                                       class="form-control" required/>
                                                <span
                                                    class="input-group-text">{{ setting('site_currency','global') }}</span>
                                            </div>
                                        </div>

                                        <div class="col-xl-12 fixed-amount @if($schema->type == 'range') hidden @endif">
                                            <label class="box-input-label" for="">{{ __('Amount:') }}</label>
                                            <div class="input-group joint-input">
                                                <input type="text" name="fixed_amount"
                                                       value="{{ $schema->fixed_amount }}"
                                                       oninput="this.value = validateDouble(this.value)"
                                                       class="form-control"/>
                                                <span
                                                    class="input-group-text">{{ setting('site_currency','global') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Return Of Interest:') }}</label>
                                        <div class="position-relative">
                                            <input
                                                type="text"
                                                class="box-input"
                                                placeholder="Number"
                                                name="return_interest"
                                                value="{{$schema->return_interest}}"
                                                oninput="this.value = validateDouble(this.value)"
                                                required
                                            />
                                            <div class="prcntcurr">
                                                <select name="interest_type" class="form-select" id="">
                                                    <option value="percentage"
                                                            @if($schema->interest_type == 'percentage') selected @endif>{{ __('%') }}</option>
                                                    <option value="fixed"
                                                            @if($schema->interest_type == 'fixed') selected @endif>{{ $currencySymbol }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Return Period:') }}</label>
                                        <select name="return_period" class="form-select" id="">
                                            @foreach($schedules as $schedule)
                                                <option value="{{$schedule->id}}"
                                                        @if($schema->return_period == $schedule->id) selected @endif>{{$schedule->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Return Type:') }}</label>
                                        <div class="switch-field same-type">
                                            <input
                                                type="radio"
                                                id="return-type-period"
                                                name="return_type"
                                                value="period"
                                                @if($schema->return_type == 'period') checked @endif
                                            />
                                            <label for="return-type-period">{{ __('Period') }}</label>
                                            <input
                                                type="radio"
                                                id="return-type-lifetime"
                                                name="return_type"
                                                @if($schema->return_type == 'lifetime') checked @endif
                                                value="lifetime"
                                            />
                                            <label for="return-type-lifetime">{{ __('Lifetime') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-xl-6 number-period @if($schema->return_type == 'lifetime') hidden @endif">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Number of Period:') }}</label>
                                        <div class="input-group joint-input">
                                            <input
                                                type="text"
                                                name="number_of_period"
                                                value="{{$schema->number_of_period}}"
                                                onkeypress="return validateNumber(event)"
                                                class="form-control"
                                                placeholder="Total Repeat Time"
                                                required
                                            />
                                            <span class="input-group-text">{{ __('Times') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Capital Back:') }}</label>
                                                <div class="switch-field">
                                                    <input
                                                        type="radio"
                                                        id="radio-seven"
                                                        name="capital_back"
                                                        value="1"
                                                        @if($schema->capital_back) checked @endif
                                                    />
                                                    <label for="radio-seven">{{ __('Yes') }}</label>
                                                    <input
                                                        type="radio"
                                                        id="radio-eight"
                                                        name="capital_back"
                                                        value="0"
                                                        @if(!$schema->capital_back) checked @endif
                                                    />
                                                    <label for="radio-eight">{{ __('No') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Featured:') }}</label>
                                                <div class="switch-field">
                                                    <input
                                                        type="radio"
                                                        id="featured-yes"
                                                        name="featured"
                                                        value="1"
                                                        @if($schema->featured) checked @endif
                                                    />
                                                    <label for="featured-yes">{{ __('Yes') }}</label>
                                                    <input
                                                        type="radio"
                                                        id="featured-no"
                                                        name="featured"
                                                        value="0"
                                                        @if(!$schema->featured) checked @endif
                                                    />
                                                    <label for="featured-no">{{ __('No') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Status:') }}</label>
                                                <div class="switch-field">
                                                    <input
                                                        type="radio"
                                                        id="radio-five"
                                                        name="status"
                                                        value="1"
                                                        @if($schema->status) checked @endif
                                                    />
                                                    <label for="radio-five">{{ __('Active') }}</label>
                                                    <input
                                                        type="radio"
                                                        id="radio-six"
                                                        name="status"
                                                        value="0"
                                                        @if(!$schema->status) checked @endif
                                                    />
                                                    <label for="radio-six">{{ __('Deactivate') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn-sm primary-btn w-100">
                                        {{ __('Update Schema') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $("#fixed-type").on('click',function () {
            $("input[name='fixed_amount']").prop('required', true);
            $("input[name='min_amount']").removeAttr('required');
            $("input[name='max_amount']").removeAttr('required');

            $(".min-amount").addClass('hidden');
            $(".max-amount").addClass('hidden');
            $(".fixed-amount").removeClass('hidden');
        });
        $("#range-type").on('click',function () {

            $("input[name='fixed_amount']").removeAttr('required');
            $("input[name='min_amount']").prop('required', true);
            $("input[name='max_amount']").prop('required', true);

            $(".fixed-amount").addClass('hidden');
            $(".min-amount").removeClass('hidden');
            $(".max-amount").removeClass('hidden');
        });

        $("#featured-no").on('click',function () {
            $("input[name='badge']").removeAttr('required');
            var schemaName = $(".schema-name");
            $(".schema-badge").addClass('hidden');
            schemaName.removeClass('col-xl-6');
            schemaName.addClass('col-xl-12');

        });
        $("#featured-yes").on('click',function () {
            $("input[name='badge']").prop('required', true);
            var schemaName = $(".schema-name");
            $(".schema-badge").removeClass('hidden');
            schemaName.removeClass('col-xl-12');
            schemaName.addClass('col-xl-6');
        });


        $("#return-type-period").on('click',function () {
            $("input[name='number_of_period']").prop('required', true);
            $(".number-period").removeClass('hidden');
        });

        $("#return-type-lifetime").on('click',function () {
            $("input[name='number_of_period']").removeAttr('required');
            $(".number-period").addClass('hidden');
        });

    </script>
@endsection
