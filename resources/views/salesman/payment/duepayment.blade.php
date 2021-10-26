@extends('salesman.layout.app')
@section('title', 'Due Payment List')
@push('headerscript')
    <!-- DataTables -->
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <link href="{{asset('theme/datatables/responsive.bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>

    <style>
        .select {
            margin-top: 12px !important;
            margin-bottom: 3px;
            background-color: white !important;
        }


    </style>
@endpush

@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-2">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="block-header m-t-20 ">
                            <h2>Due Payment List</h2>
                        </div>
                    </div>
                </div>


            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table id="datatables" class="table table-striped table-bordered dt-responsive nowrap"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Id</th>
                                    <th>Customer Name</th>
                                    <th>Mobile No</th>
                                    <th>Sale Date</th>
                                    <th>Sale Days</th>
                                    <th>Due Amount</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total Due Amount</th>
                                    <th></th>
                                </tr>

                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('footerscript')
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>

    <script src="{{asset('theme/datatables/dataTables.responsive.min.js')}}"></script>
    <script>
        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('salesmanduepayment.getData') }}',

                },

                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(api.column(6).footer()).html(json.due_amount).css('color', 'red');
                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'sale_date', name: 'sale_date'},
                    {data: 'sale_days', name: 'sale_days'},
                    {data: 'due_amount', name: 'due_amount'}
                ]
            });
        });


    </script>
@endpush




