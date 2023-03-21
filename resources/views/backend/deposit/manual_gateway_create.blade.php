@extends('backend.deposit.index')
@section('title')
    {{ __('Manual gateway') }}
@endsection
@section('deposit_content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="site-card">
                    <div class="site-card-body">
                        <form action="{{ route('admin.gateway.manual.store') }}" class="row" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="site-input-groups">
                                            <label class="box-input-label" for="">{{ __('Add Gateway Logo:') }}</label>
                                            <div class="wrap-custom-file">
                                                <input
                                                    type="file"
                                                    name="logo"
                                                    id="logo"
                                                    accept=".gif, .jpg, .png"
                                                />
                                                <label for="logo">
                                                    <img
                                                        class="upload-icon"
                                                        src="{{ asset('global/materials/upload.svg') }}"
                                                        alt=""
                                                    />
                                                    <span>{{ __('Upload Logo') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{ __('Name:') }}</label>
                                    <input
                                        type="text"
                                        class="box-input"
                                        name="name"
                                    />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{ __('Code Name:') }}</label>
                                    <input
                                        type="text"
                                        class="box-input"
                                        name="gateway_code"
                                    />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{ __('Currency:') }}</label>
                                    <input
                                        type="text"
                                        class="box-input"
                                        name="currency"
                                        id="currency"
                                    />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{ __('Currency Symbol:') }}</label>
                                    <input
                                        type="text"
                                        class="box-input"
                                        name="currency_symbol"
                                        id="currency"
                                    />
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups row">
                                    <div class="col-xl-12">
                                        <label class="box-input-label" for="">{{ __('Convention Rate:') }}</label>
                                        <div class="input-group joint-input">
                                            <span class="input-group-text">{{'1 '.' '.$currency. ' ='}} </span>
                                            <input type="text" class="form-control" name="rate"/>
                                            <span class="input-group-text" id="currency-selected"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="site-input-groups position-relative">
                                    <label class="box-input-label" for="">{{ __('Charges:') }}</label>
                                    <div class="position-relative">
                                        <input type="text" class="box-input"
                                               oninput="this.value = validateDouble(this.value)" name="charge"/>
                                        <div class="prcntcurr">
                                            <select name="charge_type" class="form-select">
                                                <option value="percentage">{{ __('%') }}</option>
                                                <option value="fixed">{{ $currencySymbol }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{ __('Minimum Deposit:') }}</label>
                                    <input type="text" name="minimum_deposit" class="box-input"/>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="site-input-groups">
                                    <label class="box-input-label" for="">{{ __('Maximum Deposit:') }}</label>
                                    <input
                                        type="text"
                                        name="maximum_deposit"
                                        class="box-input"
                                    />
                                </div>
                            </div>

                            <div class="col-xl-3">
                                <a href="javascript:void(0)" id="generate"
                                   class="site-btn-xs primary-btn mb-3">{{ __('Add Field option') }}</a>
                            </div>
                            <div class="addOptions">

                            </div>

                            <div class="col-xl-12">
                                <div class="site-input-groups fw-normal">
                                    <label for="" class="box-input-label">{{ __('Payment Details:') }}</label>
                                    <div class="site-editor">
                                        <textarea class="summernote" name="payment_details"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                        <div class="site-input-groups">
                                            <label class="box-input-label" for="">{{ __('Status:') }}</label>
                                            <div class="switch-field">
                                                <input
                                                    type="radio"
                                                    id="radio-five"
                                                    name="status"
                                                    value="1"
                                                    checked
                                                />
                                                <label for="radio-five">{{ __('Active') }}</label>
                                                <input
                                                    type="radio"
                                                    id="radio-six"
                                                    name="status"
                                                    value="0"

                                                />
                                                <label for="radio-six">{{ __('Deactivate') }}</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <button type="submit" class="site-btn primary-btn w-100">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function (e) {
            var i = 0;
            "use strict";

            $("#currency").on('keyup',function () {
                $('#currency-selected').text(this.value);
            });

            $("#generate").on('click', function () {
                ++i;
                var form = `<div class="mb-4">
                  <div class="option-remove-row row">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                      <div class="site-input-groups">
                        <input name="credentials[` + i + `][name]" class="box-input" type="text" value="" required placeholder="Field Name">
                      </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                      <div class="site-input-groups">
                        <select name="credentials[` + i + `][type]" class="form-select form-select-lg mb-3">
                            <option value="text">Input Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="file">File upload</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                      <div class="site-input-groups mb-0">
                        <select name="credentials[` + i + `][validation]" class="form-select form-select-lg mb-1">
                            <option value="required">Required</option>
                            <option value="nullable">Optional</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-xl-1 col-lg-6 col-md-6 col-sm-6 col-12">
                      <button class="delete-option-row delete_desc" type="button">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                    </div>
                  </div>`;
                $('.addOptions').append(form)
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.option-remove-row').parent().remove();
            });
        });
    </script>
@endsection
