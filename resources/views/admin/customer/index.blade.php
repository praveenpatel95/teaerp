@extends('admin.layout.app')
@section('title', 'ग्राहक लिस्ट')
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
                            <h2 >ग्राहक लिस्ट</h2>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group form-group m-t-15 ">
                        <label class="input-group-addon  ">रूट द्वारा फ़िल्टर करें</label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group fg-line m-l-10">
                        {!! Form::select('route_id', [''=>'रूट चुने']+$route, old('route_id'), ['id'=>'route_id'  , 'class'=>'selectpicker select ' ,'data-live-search'=> 'true'  ,'data-dropup-auto'=>"false"]) !!}
                    </div>
                </div>
                <div class="col-sm-5 pull-right">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('customer.create')}}"><i class="zmdi zmdi-plus"></i> ग्राहक जोड़ें</a>
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
                                    <th>ग्राहक आईडी</th>
                                    <th>नाम</th>
                                    <th>पिता का नाम</th>
                                    <th>रूट</th>
                                    <th>मोबाइल नंबर</th>
                                    <th>बकाया राशि</th>
                                    <th>रिटर्न तारीख़ </th>
                                    <th>स्थिति</th>
                                    <th>ऐक्शन </th>
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
                    url: '{{ route('customer.getData') }}',
                    data: function (d) {
                        d.route = $('#route_id').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'id', name: 'customers.id'},
                    {data: 'customer_name', name: 'customers.customer_name'},
                    {data: 'father_name', name: 'customers.father_name'},
                    {data: 'route_name', name: 'routes.route_name'},
                    {data: 'mobile_no', name: 'customers.mobile_no'},
                    {data: 'due_balance', name: 'customers.due_balance'},
                    {data: 'return_date', name: 'customers.return_date'},
                    {data: 'status', name: 'customers.status'},
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
                        url: '{{ url('admin/customer') }}/' + id,
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
        $("#route_id").on('change', function () {
            $('#datatables').DataTable().draw(false)
        });
    </script>
@endpush
