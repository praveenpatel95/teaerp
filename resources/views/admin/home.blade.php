@extends('admin.layout.app')
@section('title','
डैशबोर्ड')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <style>
        .dashboardfilter {
            margin-top: 12px !important;
            margin-bottom: 2px;
            background-color: white !important;
        }
        .daterangepicker {
            margin-top: 130px !important;
        }
    </style>
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-3">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="block-header m-t-20 ">
                            <h2>डैशबोर्ड </h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control dashboardfilter start-date" id="start"  value="{{date('d-M-Y')}}" />
                        <span class="input-group-addon bg-custom text-white b-0">to</span>
                        <input type="text" class="form-control dashboardfilter end-date" id="end" value="{{date('d-M-Y')}}"/>
                    </div>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-info btn-icon-text " style="margin-top: 13px" onclick="SearchIt()"><i class="zmdi zmdi-filter-center-focus"></i> फ़िल्टर करे
                    </button>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="mini-charts" id="dashboardData">
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
                                    <th>स्टॉक असाइन </th>
                                    <th> सेलिंग </th>
                                    <th> कलेक्शन  </th>
                                    <th> एक्सपेक्टेड   </th>
                                    <th> बैलेंस   </th>
                                    <th>सेल कमीशन</th>
                                    <th>पेमेंट कमीशन</th>
                                    <th> वेतन   </th>

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
    </section>
@endsection
@push('footerscript')
    <script src="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            callAajax();
        });

        function SearchIt(){
            callAajax();
            $('#datatables').DataTable().draw(false)
        }

        function callAajax() {
            var startDate = $("#start").val();
            var endDate = $("#end").val();
            $.ajax({
                'url': "{{route('admin.DashboardgetData')}}",
                type: 'get',
                data: {'startDate': startDate,'endDate':endDate},
                success: function (response) {
                    $("#dashboardData").html(response)
                },
            });
        }

        $('.start-date').datetimepicker({
                format: "DD.MMM.YYYY",
                maxDate: new Date()
        });

        $('.end-date').datetimepicker({
            format: "DD.MMM.YYYY",
        });

    </script>
    <script>
        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('admin.salesmangetData') }}',
                    data: function (d) {
                        d.startDate = $('#start').val();
                        d.endDate = $('#end').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'assignStock', name: 'assignStock'},
                    {data: 'SoldStock', name: 'SoldStock'},
                    {data: 'PaidAmount', name: 'PaidAmount'},
                    {data: 'Total_amount', name: 'Total_amount'},
                    {data: 'balance', name: 'balance'},
                    {data: 'Commission', name: 'Commission'},
                    {data: 'payment_commission', name: 'payment_commission'},
                    {data: 'salary_amount', name: 'salary_amount'},
                ]
            });
        });

    </script>
@endpush
