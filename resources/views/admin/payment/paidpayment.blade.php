@extends('admin.layout.app')
@section('title', 'जमा राशि लिस्ट')
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

        .addBtn {
            margin-top: 13px !important;
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
                            <h2>जमा राशि लिस्ट</h2>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control salesman start-date" id="start"
                              />
                        <span class="input-group-addon bg-custom text-white b-0">to</span>
                        <input type="text" class="form-control salesman end-date" id="end" />
                    </div>
                </div>
                <div class="col-sm-2 ">
                    <div class="form-group fg-line">
                        {!! Form::select('salesman_id', [''=>'सेल्समैन द्वारा फ़िल्टर करें']+$salesman, old('salesman_id'), ['required','id'=>'salesman_id'  , 'class'=>'selectpicker salesman' ,'data-live-search'=> 'true'  ,'data-dropup-auto'=>"false"]) !!}
                    </div>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-info btn-icon-text " style="margin-top: 13px" onclick="SearchIt()"><i class="zmdi zmdi-filter-center-focus"></i> फ़िल्टर करे
                    </button>
                </div>
                <div class="col-sm-1 pull-right m-t-15">
                    <button type="button" class="btn btn-success btn-sm  " style="float: right" onclick="ExportIt()"><i
                            class="zmdi zmdi-collection-pdf"></i> Export</button>
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
                                    <th>ग्राहक आईडी</th>
                                    <th>सेल न.</th>
                                    <th>ग्राहक का नाम</th>
                                    <th>पिता का नाम</th>
                                    <th>मोबाइल नंबर</th>
                                    <th>सेल की तिथी</th>
                                    <th>पेमेंट का तरीका</th>
                                    <th>पेमेंट की तिथि</th>
                                    <th>कुल राशि</th>
                                    <th>जमा राशि</th>
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
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>कुल जमा राशि</th>
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
    <script src="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
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
                    url: '{{ route('paidpayment.getData') }}',
                    data: function (d) {
                        d.salesman_id = $('#salesman_id').val();
                        d.startDate = $('#start').val();
                        d.endDate = $('#end').val();
                    }
                },
                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(api.column(10).footer()).html(json.pay_amount).css('color', 'red');
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'sale_no', name: 'sale_no'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'sale_date', name: 'sale_date'},
                    {data: 'paymode', name: 'paymode'},
                    {data: 'pay_date', name: 'pay_date'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'pay_amount', name: 'pay_amount'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });

        function SearchIt() {

            $('#datatables').DataTable().draw(false)
        }


        function ExportIt() {
            var startDate = $("#start").val();
            var endDate = $("#end").val();
            var salesman = $('#salesman_id').val();
            window.location.href = "{{route('PaidPaymentExporttToExcel')}}?&startDate="+startDate+"&salesman="+salesman+"&endDate="+endDate;
        }
    </script>
@endpush
