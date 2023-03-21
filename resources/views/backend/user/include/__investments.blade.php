<div
    class="tab-pane fade"
    id="pills-transfer"
    role="tabpanel"
    aria-labelledby="pills-transfer-tab"
>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Investments') }}</h4>
                </div>
                <div class="site-card-body table-responsive">
                    <div class="site-datatable">
                        <table id="user-investment-dataTable" class="display data-table">
                            <thead>
                            <tr>
                                <th>{{ __('Icon') }}</th>
                                <th>{{ __('Schema') }}</th>
                                <th>{{ __('ROI') }}</th>
                                <th>{{ __('Profit') }}</th>
                                <th>{{ __('Capital Back') }}</th>
                                <th>{{ __('Timeline') }}</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('single-script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#user-investment-dataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.investments',$user->id) }}",
                columns: [
                    {data: 'icon', name: 'icon'},
                    {data: 'schema', name: 'schema'},
                    {data: 'rio', name: 'rio'},
                    {data: 'profit', name: 'profit'},
                    {data: 'capital_back', name: 'capital_back'},
                    {data: 'next_profit_time', name: 'next_profit_time'},
                ]
            });
        })(jQuery);
    </script>
@endpush
