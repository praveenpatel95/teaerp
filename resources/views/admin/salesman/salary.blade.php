@extends('admin.layout.app')
@section('title', 'सेल्समेन वेतन')
@push('headerscript')
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css"
          href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/daterangepicker.css')}}"/>

    <style>
        .created_date {
            padding-left: 30px !important;
        }

        .form-control {

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
                <div class="block-header m-t-20 m-l-5">
                    <div class="col-sm-2">
                        <div class="block-header m-t-5 ">
                            <h2>सेल्समेन वेतन</h2>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="input-daterange input-group">
                            <input type="text" class="form-control salesman month" id="month" style="padding-left: 20px"
                                   value="{{date('M-Y')}}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table id="datatables" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>सेल्समेन का नाम</th>
                                    <th>वेतन</th>
                                    <th>काम के घंटे</th>
                                    <th>कुल वेतन</th>
                                    <th>सेल कमीशन</th>
                                    <th>पेमेंट कमीशन</th>
                                    <th>कुल राशि</th>
                                    <th>एडवांस राशि</th>
                                    <th>बकाया राशि</th>
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
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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
    <script
        src="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript">
        $('.month').datetimepicker({
            format: 'MMMM-YYYY',
            maxDate: new Date()
        });
    </script>
    <script>
        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('salesmanSalaryGetdata') }}',
                    data: function (d) {
                        d.month = $('#month').val();
                    }
                },

                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(api.column(7).footer()).html(json.total).css('color', 'red');
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'salary_amount', name: 'salary_amount'},
                    {data: 'work_hours', name: 'work_hours'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'sale_commission', name: 'sale_commission'},
                    {data: 'payment_commission', name: 'payment_commission'},
                    {data: 'total_salary', name: 'total_salary'},
                    {data: 'advance_amount', name: 'advance_amount'},
                    {data: 'due_amount', name: 'due_amount'},
                ]
            });
        });

        $('#month').on('dp.change', function (e) {
            $('#datatables').DataTable().draw(false)
        });
    </script>
@endpush
