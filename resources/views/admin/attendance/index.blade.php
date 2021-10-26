@extends('admin.layout.app')
@section('title', 'अटेंडेंस')
@push('headerscript')
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css"
          href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/daterangepicker.css')}}"/>

    <style>

        .created_date  {
            padding-left: 30px !important;
        }

        .start-date  {
            padding-left: 30px !important;
        }
        .form-control {

            background-color: white !important;
        }
        .addBtn {
            margin-top: 13px !important;
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
                            <h2>अटेंडेंस</h2>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control salesman start-date" id="start" value="{{date('d-M-Y')}}"
                            />
                            <span class="input-group-addon bg-custom text-white b-0">to</span>
                            <input type="text" class="form-control salesman end-date" id="end" value="{{date('d-M-Y')}}" />
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-info btn-icon-text " style="margin-top: 2px" onclick="SearchIt()"><i class="zmdi zmdi-filter-center-focus"></i> फ़िल्टर करे
                        </button>
                    </div>

                    <div class="col-sm-5">
                        <div class="btn-group pull-right ">
                            <a class="btn btn-primary btn-block" href="#addattendance" data-toggle="modal"><i
                                    class="zmdi zmdi-plus"> </i> अटेंडेंस जोड़ें</a>
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
                                    <th>काम के घंटे</th>
                                    <th>अटेंडेंस</th>
                                    <th>अटेंडेंस तिथि</th>
                                    <th>कमीशन</th>
                                    <th>वेतन</th>
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
    <!-- Modal For Add Attendance -->
    <div class="modal fade" id="addattendance" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">अटेंडेंस जोड़ें</h4>
                </div>
                {!! Form::open(['route' => 'attendance.store','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4 ">
                            <div class="form-group fg-line">
                                <label for="attendance_date">दिनांक</label>
                                <input type="text" name="attendance_date" id="dddddd"
                                       value="{{date('d-m-Y')}}" class="form-control created_date ">
                                <div style="color: red">{{$errors->first('attendance_date')}}</div>
                            </div>
                        </div>
                        <table class="table table-bordered m-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th  width="20%">नाम</th>
                                <th>काम के घंटे</th>
                                <th width="29%">प्रकार</th>
                                <th>कमीशन</th>
                            </tr>
                            </thead>
                            <tbody id="datas">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" id="submitBtn" class="btn btn-primary ">सेव</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@push('footerscript')

    <script
        src="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>

    <script type="text/javascript">
        $('.number').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
            }
        });
        $('.floatnumber').on('input', function () {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        });

        $('.created_date').datetimepicker({
            format: 'DD-MM-YYYY',
            maxDate: new Date()
        });

        $('.start-date').datetimepicker({
            format: "DD.MMM.YYYY",
            maxDate: new Date()
        });

        $('.end-date').datetimepicker({
            format: "DD.MMM.YYYY",
        });
    </script>
    <script>
        $(document).ready(function () {
            Attendancedata();
        });
        function Attendancedata() {
            var attendance_date = $(".created_date").val();
            $.ajax({
                url: "{{route('attendance.getData')}}",
                type: 'get',
                data: {'attendance_date': attendance_date},
                success: function (result) {
                    $("#datas").html(result);
                }
            });
        }
        $('.created_date').on('dp.change', function (e) {

            Attendancedata();
        });
    </script>
    <script>

        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('attendanceview.getData') }}',
                    data: function (d) {
                        d.startDate = $('#start').val();
                        d.endDate = $('#end').val();
                    }
                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'work_hours', name: 'work_hours'},
                    {data: 'type', name: 'type'},
                    {data: 'attendance_date', name: 'attendance_date'},
                    {data: 'commission', name: 'commission'},
                    {data: 'salary', name: 'salary'},
                ]
            });
        });



        function SearchIt() {

            $('#datatables').DataTable().draw(false)
        }

    </script>
@endpush
