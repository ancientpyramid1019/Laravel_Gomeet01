<div
    class="tab-pane fade"
    id="pills-deposit"
    role="tabpanel"
    aria-labelledby="pills-deposit-tab"
>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Earnings') }}</h4>
                    <div
                        class="card-header-info">{{ __('Total Earnings:') }} {{ $user->totalProfit() }} {{ $currency }}</div>
                </div>
                <div class="site-card-body table-responsive">
                    <div class="site-datatable">
                        <table id="user-profit-dataTable" class="display data-table">
                            <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Profit From') }}</th>
                                <th>{{ __('Description') }}</th>
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

            var table = $('#user-profit-dataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.all-profits',$user->id) }}",
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'final_amount', name: 'final_amount'},
                    {data: 'type', name: 'type'},
                    {data: 'profit_from', name: 'profit_from'},
                    {data: 'description', name: 'description'},

                ]
            });


        })(jQuery);
    </script>
@endpush
