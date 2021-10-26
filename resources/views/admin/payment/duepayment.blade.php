@extends('admin.layout.app')
@section('title', 'बकाया राशि लिस्ट')
@push('headerscript')
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <style>
        .select {
            margin-top: 12px !important;
            margin-bottom: 3px;
            background-color: white !important;
        }
        .btn-icon {
            width: 30px !important;
            line-height: 14px !important;
            height: 28px !important;
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
                            <h2>बकाया राशि लिस्ट</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group form-group m-t-15 ">
                        <label class="input-group-addon  ">लाइन द्वारा फ़िल्टर करें</label>

                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group fg-line m-l-10">
                        {!! Form::select('line_id', [''=>'लाइन चुने']+$line, old('line_id'), ['id'=>'line_id'  , 'class'=>'selectpicker select ' ,'data-live-search'=> 'true'  ,'data-dropup-auto'=>"false"]) !!}
                    </div>
                </div>
                <div class="col-sm-1 pull-right m-t-20">
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
                                    <th>ग्राहक का नाम</th>
                                    <th>पिता का नाम</th>
                                    <th>मोबाइल नंबर</th>
                                    <th>सेल की तिथी</th>
                                    <th>सेल दिन</th>
                                    <th>कुल राशि</th>
                                    <th>जमा राशि</th>
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
                                    <th>कुल बकाया राशि</th>
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
    <script>
        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('duepayment.getData') }}',
                    data: function (d) {
                        d.line = $('#line_id').val();
                    }
                },

                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(api.column(10).footer()).html(json.due_amount).css('color', 'red');
                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'sale_no', name: 'sale_no'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'sale_date', name: 'sale_date'},
                    {data: 'sale_days', name: 'sale_days'},
                    {data: 'total', name: 'total'},
                    {data: 'paid', name: 'paid'},
                    {data: 'due_amount', name: 'due_amount'},
                ]
            });
        });

        $("#line_id").on('change', function () {
            $('#datatables').DataTable().draw(false)
        });

        function ExportIt() {
            var line = $('#line_id').val();
            window.location.href = "{{route('DuePaymentExporttToExcel')}}?&line="+line;
        }
    </script>
@endpush




