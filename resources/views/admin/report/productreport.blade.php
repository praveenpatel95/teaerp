@extends('admin.layout.app')
@section('title', 'प्रोडक्ट आइटम रिपोर्ट')
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
        .daterangepicker {
            margin-top: 130px !important;
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
                            <h2>प्रोडक्ट आइटम रिपोर्ट</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control salesman start-date" id="start"
                             />
                        <span class="input-group-addon bg-custom text-white b-0">to</span>
                        <input type="text" class="form-control salesman end-date" id="end" />
                    </div>
                </div>
                <div class="col-sm-2 ">
                    <div class="form-group fg-line">
                        {!! Form::select('salesman_id', [''=>'सेल्समैन द्वारा फ़िल्टर करें']+$salesman, old('salesman_id'), ['required','id'=>'salesman_id'  , 'class'=>'selectpicker salesman' ,'data-live-search'=> 'true' ,'data-dropup-auto'=>"false"]) !!}
                    </div>
                </div>
                <div class="col-sm-2 ">
                    <div class="form-group fg-line">
                        {!! Form::select('product_id', [''=>'प्रोडक्ट द्वारा फ़िल्टर करें']+$product, old('product_id'), ['required','id'=>'product_id'  , 'class'=>'selectpicker salesman' ,'data-live-search'=> 'true' ,'data-dropup-auto'=>"false"]) !!}
                    </div>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-info btn-icon-text " style="margin-top: 13px" onclick="SearchIt()"><i class="zmdi zmdi-filter-center-focus"></i> फ़िल्टर करे
                    </button>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-1 pull-right m-t-15">
                    <button type="button" class="btn btn-success btn-sm " style="float: right" onclick="ExportIt()"><i
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
                                    <th>सेल की तिथी </th>
                                    <th>ग्राहक का नाम</th>
                                    <th>सेल्समेन</th>
                                    <th>प्रोडक्ट</th>
                                    <th>मात्रा</th>
                                    <th>कीमत</th>
                                    <th>कुल कीमत</th>
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
                    url: '{{ route('productReportGetdata') }}',
                    data: function (d) {
                        d.startDate = $('#start').val();
                        d.endDate = $('#end').val();
                        d.salesman_id = $('#salesman_id').val();
                        d.product_id = $('#product_id').val();
                    }
                },
                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();

                    $(api.column(9).footer()).html(json.total_amount).css('color', 'red');
                    $(api.column(8).footer()).html(json.total_price).css('color', 'red');
                    $(api.column(7).footer()).html(json.total_qty ).css('color', 'red');
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'customer_id', name: 'customers.id'},
                    {data: 'sale_no', name: 'sales.sale_no'},
                    {data: 'sale_date', name: 'sales.sale_date'},
                    {data: 'customer_name', name: 'customers.customer_name'},
                    {data: 'name', name: 'salesmen.name'},
                    {data: 'product_name', name: 'products.product_name'},
                    {data: 'qty', name: 'sale_products.qty'},
                    {data: 'price', name: 'sale_products.price'},
                    {data: 'total_price', name: 'sale_products.total_price'},
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
            var product = $('#product_id').val();
            window.location.href = "{{route('ProductReportExporttToExcel')}}?&startDate="+startDate+"&endDate="+endDate+"&salesman="+salesman+"&product="+product;
        }

    </script>
@endpush




