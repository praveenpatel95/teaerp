@extends('salesman.layout.app')
@section('title', 'Payments List')
@push('headerscript')
    <!-- DataTables -->
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <link href="{{asset('theme/datatables/responsive.bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    {{--datepicker--}}
    <link rel="stylesheet" type="text/css"
          href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/daterangepicker.css')}}"/>
    <style>
        .input-group .form-control {
            margin-top: 12px !important;
            margin-bottom: 3px;
            background-color: white !important;
        }

        .daterangepicker {
            margin-top: 140px !important;
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
                            <h2>Payments List</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-group form-group">
                        <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                        <div class="dtp-container">
                            <input type="text" id="created_at" class="form-control created_date " name="created_at"
                                   value="">
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
                                    <th>Pay Mode</th>
                                    <th>Pay Date</th>
                                    <th>Paid Amount</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Total Paid Amount </th>
                                    <th></th>
                                    <th width="10%"></th>
                                </tr>
                                </tfoot>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('footerscript')
    <script type="text/javascript"
            src="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/daterangepicker.js')}}"></script>
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>

    <script src="{{asset('theme/datatables/dataTables.responsive.min.js')}}"></script>
    <script>
        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('salesmanpaidpayment.getData') }}',
                    data: function (d) {
                        d.daterange = $('#created_at').val();

                    }
                },

                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(api.column(7).footer()).html(json.pay_amount).css('color', 'red');
                },
                    columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'sale_date', name: 'sale_date'},
                    {data: 'paymode', name: 'paymode'},
                    {data: 'pay_date', name: 'pay_date'},
                    {data: 'pay_amount', name: 'pay_amount'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });

        /*Date Range For Search*/
        $('.created_date').daterangepicker({
            locale: {
                format: "DD.MMM.YYYY"
            },

            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $("#created_at").on('change', function () {
            $('#datatables').DataTable().draw(false)
        });
    </script>
@endpush




