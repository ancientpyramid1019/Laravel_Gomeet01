<div
    class="modal fade"
    id="editTarget"
    tabindex="-1"
    aria-labelledby="editTargetModalLabel"
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
                <form action="{{ route('admin.referral.target-update') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="target-id">
                    <div class="popup-body-text">
                        <h3 class="title mb-4">{{ __('Edit Target') }}</h3>
                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Target Name:') }}</label>
                            <input type="text" name="name" class="box-input mb-0 target-name" required=""/>
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

            </div>
        </div>
    </div>
</div>
