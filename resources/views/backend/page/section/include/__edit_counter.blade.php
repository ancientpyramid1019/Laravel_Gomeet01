<div
    class="modal fade"
    id="editContent"
    tabindex="-1"
    aria-labelledby="editHowItWorksModalLabel"
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
                    <h3 class="title mb-4">{{ __('Edit') }}</h3>
                    <form action="{{ route('admin.page.content-update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="updatedId" name="id">
                        <div class="site-input-groups">
                            <label class="box-input-label" for="">Icon:</label>
                            <div class="wrap-custom-file">
                                <input type="file" name="icon" id="iconEdit" accept=".gif, .jpg, .png"/>
                                <label for="iconEdit" id="image-old">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt=""/>
                                    <span>{{ __('Update Icon') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Title:') }}</label>
                            <input type="text" name="title" class="box-input mb-0 title0" required=""/>
                        </div>
                        <div class="site-input-groups">
                            <label for="" class="box-input-label description">{{ __('Counter Number:') }}</label>
                            <input type="text" name="description" onkeypress="return validateNumber(event)"
                                   class="box-input mb-0 description" required="" placeholder="52"/>
                        </div>

                        <div class="action-btns">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i icon-name="check"></i>
                                {{ __(' Save Changes') }}
                            </button>
                            <a
                                href="#"
                                class="site-btn-sm red-btn"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                <i icon-name="x"></i>
                                {{ __(' Close') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
