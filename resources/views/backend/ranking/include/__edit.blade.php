<div
    class="modal fade"
    id="editRanking"
    tabindex="-1"
    aria-labelledby="editRankingModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <form id="rankingEditForm" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="popup-body-text">
                        <h3 class="title mb-4">{{ __('Edit Ranking') }}</h3>
                        <div class="site-input-groups">
                            <label class="box-input-label" for="">{{ __('Ranking Icon:') }}</label>
                            <div class="wrap-custom-file">
                                <input type="file" name="icon" id="image6" accept=".gif, .jpg, .png"/>
                                <label for="image6" id="image-old">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                    <span>{{ __('Update Icon') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Ranking:') }}</label>
                            <input type="text" name="ranking" class="box-input mb-0 ranking" required=""/>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Ranking Name:') }}</label>
                            <input type="text" name="ranking_name" class="box-input mb-0 ranking-name" required=""/>
                        </div>

                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Minimum Earning:') }}</label>
                            <div class="input-group joint-input">
                                <input type="text" class="form-control minimum-earnings" name="minimum_earnings" oninput="this.value = validateDouble(this.value)">
                                <span class="input-group-text">{{ setting('site_currency','global') }}</span>
                            </div>
                        </div>


                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Bonus:') }}</label>
                            <div class="input-group joint-input">
                                <input type="text" class="form-control bonus" name="bonus" oninput="this.value = validateDouble(this.value)">
                                <span class="input-group-text">{{ setting('site_currency','global') }}</span>
                            </div>
                        </div>


                        <div class="site-input-groups mb-0">
                            <label for="" class="box-input-label">{{ __('Description:') }}</label>
                            <textarea name="description" class="form-textarea description"></textarea>
                        </div>

                        <div class="site-input-groups">
                            <label class="box-input-label" for="">{{ __('Status:') }}</label>
                            <div class="switch-field">
                                <input type="radio" id="activeStatus" name="status" value="1">
                                <label for="activeStatus">{{ __('Active') }}</label>
                                <input type="radio" id="disableStatus" name="status" value="0">
                                <label for="disableStatus">{{ __('Disabled') }}</label>
                            </div>
                        </div>

                        <div class="action-btns">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i icon-name="check"></i>
                                {{ __('Save Changes') }}
                            </button>
                            <a
                                href="#"
                                class="site-btn-sm red-btn"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                <i icon-name="x"></i>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </div>
                </form>
                >
            </div>
        </div>
    </div>
</div>
