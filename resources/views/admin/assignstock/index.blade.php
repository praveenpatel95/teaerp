@extends('admin.layout.app')
@section('title', 'असाइन स्टॉक लिस्ट')
@push('headerscript')
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <style>
        .salesman {
            margin-top: 12px !important;
            margin-bottom: 2px;
            background-color: white !important;
        }

        .assignbtn {
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
                            <h2>असाइन स्टॉक लिस्ट</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="block-header m-t-20 m-l-25 ">
                        <div class="block-header m-t-20 ">
                            <h2>सेल्समैन द्वारा फ़िल्टर करें</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 ">
                    <div class="form-group fg-line">
                        {!! Form::select('salesman_id', [''=>'सेल्समैन चुने']+$salesman, old('salesman_id'), ['required','id'=>'salesman_id'  , 'class'=>'selectpicker salesman' ,'data-live-search'=> 'true' ,'data-live-search'=>'true'  ]) !!}
                    </div>
                </div>
                <div class="col-sm-3"></div>
                <div class="col-sm-2 ">
                    <div class="input-group form-group pull-right">
                        <a class="btn btn-primary assignbtn" href="{{route('assignstock.create')}}"><i
                                class="zmdi zmdi-plus"></i> असाइन स्टॉक</a>
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
                                    <th>सेल्समैन का नाम</th>
                                    <th>प्रोडक्ट</th>
                                    <th>मात्रा (Piece)</th>
                                    <th>कुल वजन (Weight)</th>
                                    <th>असाइन तिथी</th>
                                    <th>एक्शन</th>
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
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script>
        $(function () {
            $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: '{{ route('assignstock.getData') }}',
                    data: function (d) {
                        d.salesman_id = $('#salesman_id').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'name', name: 'salesmen.name'},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'qty', name: 'purchase_price'},
                    {data: 'total_weight', name: 'total_weight'},
                    {data: 'assign_date', name: 'assign_date'},
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
                        url: '{{ url('admin/assignstock') }}/' + id,
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

        $("#salesman_id").on('change', function () {
            $('#datatables').DataTable().draw(false)
        });
    </script>
@endpush




