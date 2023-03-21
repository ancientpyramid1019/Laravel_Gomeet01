<div
    class="modal fade"
    id="addNewRanking"
    tabindex="-1"
    aria-labelledby="addNewRankingModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
                <div class="popup-body-text">
                    <h3 class="title mb-4">{{ __('Add New Ranking') }}</h3>
                    <form action="{{ route('admin.ranking.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="site-input-groups">
                            <label class="box-input-label" for="">{{ __('Ranking Icon:') }}</label>
                            <div class="wrap-custom-file">
                                <input type="file" name="icon" id="icon" accept=".gif, .jpg, .png"/>
                                <label for="icon">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                    <span>{{ __('Upload Icon') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Ranking:') }}</label>
                            <input type="text" name="ranking" value="{{ old('ranking') }}" class="box-input mb-0"
                                   placeholder="Eg: 1, 2, 3..." required=""/>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Ranking Name:') }}</label>
                            <input type="text" name="ranking_name" value="{{ old('ranking_name') }}"
                                   class="box-input mb-0" placeholder="Ranking Name" required=""/>
                        </div>


                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Minimum Earning:') }}</label>
                            <div class="input-group joint-input">
                                <input type="text" class="form-control" name="minimum_earnings" oninput="this.value = validateDouble(this.value)">
                                <span class="input-group-text">{{ setting('site_currency','global') }}</span>
                            </div>
                        </div>


                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Bonus:') }}</label>
                            <div class="input-group joint-input">
                                <input type="text" class="form-control" name="bonus" oninput="this.value = validateDouble(this.value)">
                                <span class="input-group-text">{{ setting('site_currency','global') }}</span>
                            </div>
                        </div>

                        <div class="site-input-groups mb-0">
                            <label for="" class="box-input-label">{{ __('Description:') }}</label>
                            <textarea name="description" class="form-textarea"
                                      placeholder="Description">{{ old('description') }}</textarea>
                        </div>

                        <div class="site-input-groups">
                            <label class="box-input-label" for="">{{ __('Status:') }}</label>
                            <div class="switch-field">
                                <input type="radio" id="radioRaningStatusActive" name="status" checked="" value="1">
                                <label for="radioRaningStatusActive">{{ __('Active') }}</label>
                                <input type="radio" id="radioRaningStatusDisabled" name="status" value="0">
                                <label for="radioRaningStatusDisabled">{{ __('Disabled') }}</label>
                            </div>
                        </div>

                        <div class="action-btns">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i icon-name="check"></i>
                                {{ __('Add Ranking') }}
                            </button>
                            <a href="#" class="site-btn-sm red-btn" data-bs-dismiss="modal" aria-label="Close">
                                <i icon-name="x"></i>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
