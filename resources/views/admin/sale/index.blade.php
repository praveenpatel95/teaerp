@extends('admin.layout.app')
@section('title', 'सेल लिस्ट')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <style>
        .salesman {
            margin-top: 12px !important;

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
                <div class="col-sm-1">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="block-header m-t-20 ">
                            <h2>सेल लिस्ट</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control salesman start-date" id="start"
                               {{--value="{{date('d-M-Y')}}"--}}/>
                        <span class="input-group-addon bg-custom text-white b-0">to</span>
                        <input type="text" class="form-control salesman end-date" id="end" {{--value="{{date('d-M-Y')}}"--}}/>
                    </div>
                </div>
                <div class="col-sm-2 ">
                    <div class="form-group fg-line">
                        <input type="text" name="customer_id" class="form-control salesman number" id="customer_id"
                               placeholder="ग्राहक आईडी द्वारा फ़िल्टर करें">
                    </div>
                </div>
                <div class="col-sm-2 ">
                    <div class="form-group fg-line">
                        {!! Form::select('salesman_id', [''=>'सेल्समैन द्वारा फ़िल्टर करे']+$salesman, old('salesman_id'), ['required','id'=>'salesman_id'  , 'class'=>'selectpicker salesman' ,'data-dropup-auto'=>"false" ,'data-live-search'=>'true' ]) !!}
                    </div>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-info btn-icon-text " style="margin-top: 13px" onclick="SearchIt()"><i class="zmdi zmdi-filter-center-focus"></i> फ़िल्टर करे
                    </button>
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
                                    <th>सेल न.</th>
                                    <th>ग्राहक आईडी</th>
                                    <th>ग्राहक का नाम</th>
                                    <th>पिता का नाम</th>
                                    <th>सेल की तिथी</th>
                                    <th>कुल राशि</th>
                                    <th>जमा राशि</th>
                                    <th>बकाया राशि</th>
                                    <th>पेमेंट कमीशन</th>
                                    <th width="18%">एक्शन</th>
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

        function SearchIt() {
            $('#datatables').DataTable().draw(false)
        }

        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('sale.getData') }}',
                    data: function (d) {
                        d.salesman_id = $('#salesman_id').val();
                        d.customer_id = $('#customer_id').val();
                        d.startDate = $('#start').val();
                        d.endDate = $('#end').val();
                    }
                },

                 "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();

                    $(api.column(6).footer()).html(json.total_amount).css('color', 'red');
                    $(api.column(8).footer()).html(json.due ).css('color', 'red');
                    $(api.column(7).footer()).html(json.pay_amount).css('color', 'red');
                    $(api.column(9).footer()).html(json.sale_commission).css('color', 'red');

                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'sale_no', name: 'sale_no'},
                    {data: 'customer_id', name: 'customer_id'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'sale_date', name: 'sale_date'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'pay_amount', name: 'pay_amount'},
                    {data: 'due_amount', name: 'due_amount'},
                    {data: 'sale_commission', name: 'sale_commission'},
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
                        url: '{{ url('admin/sale') }}/' + id,
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



        $('.number').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
            }
        });


    </script>
@endpush




