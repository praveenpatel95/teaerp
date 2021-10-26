@extends('admin.layout.app')
@section('title', 'खर्च लिस्ट')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <style>
        .salesman {
            margin-top: 12px !important;
            margin-bottom: 2px;
            background-color: white !important;
        }

        .backbtn {
            margin-top: 13px !important;
        }

        .
    </style>
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-5">
                <div class="col-sm-1">
                    <div class="block-header m-t-20 ">
                        <div class="block-header m-t-20 ">
                            <h2>खर्च लिस्ट</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control salesman start-date" id="start"/>
                        <span class="input-group-addon bg-custom text-white b-0">to</span>
                        <input type="text" class="form-control salesman end-date" id="end"/>
                    </div>
                </div>
                <div class="col-sm-3 ">
                    <div class="form-group fg-line">
                        {!! Form::select('salesman_id', [''=>'सेल्समैन द्वारा फ़िल्टर करें']+$salesman, old('salesman_id'), ['required','id'=>'salesman_id'  , 'class'=>'selectpicker salesman' ,'data-live-search'=> 'true' ]) !!}
                    </div>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-info btn-icon-text " style="margin-top: 13px" onclick="SearchIt()"><i class="zmdi zmdi-filter-center-focus"></i> फ़िल्टर करे
                    </button>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-1 ">
                    <div class="input-group form-group pull-right">
                        <a class="btn btn-primary btn-block backbtn" href="{{route('expense.create')}}"><i
                                class="zmdi zmdi-plus"></i> खर्च जोड़ें</a>
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
                                    <th>खर्च नाम</th>
                                    <th>डिटेल</th>
                                    <th>सेल्समेन</th>
                                    <th>दिनांक</th>
                                    <th>राशि</th>
                                    <th>ऐक्शन</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>कुल खर्च राशि</th>
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
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script
        src="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        $('.start-date').datetimepicker({
            format: "DD.MMM.YYYY",
            maxDate: new Date()
        });

        $('.end-date').datetimepicker({
            format: "DD.MMM.YYYY",
        });

        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('expense.getData') }}',
                    data: function (d) {
                        d.startDate = $('#start').val();
                        d.endDate = $('#end').val();
                        d.salesman_id = $('#salesman_id').val();
                    }
                },

                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(api.column(5).footer()).html(json.total_expense).css('color', 'red');
                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'expense_name', name: 'expense_name'},
                    {data: 'detail', name: 'detail'},
                    {data: 'name', name: 'name'},
                    {data: 'expense_date', name: 'expense_date'},
                    {data: 'amount', name: 'amount'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });

        function deleteIt(id) {

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '{{ url('admin/expense') }}/' + id,
                        type: 'delete',
                        dataType: "JSON",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function () {
                            $("#datatables").DataTable().draw(false);
                        }
                    });
                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                }

            });
        }

        function SearchIt() {

            $('#datatables').DataTable().draw(false)
        }


    </script>
@endpush




