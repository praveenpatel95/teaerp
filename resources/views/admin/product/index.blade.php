@extends('admin.layout.app')
@section('title', 'प्रोडक्ट लिस्ट')
@push('headerscript')
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('product.create')}}"><i class="zmdi zmdi-plus"></i> प्रोडक्ट जोड़े </a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2 >प्रोडक्ट लिस्ट</h2>
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
                                    <th>प्रोडक्ट का नाम </th>
                                    <th >वज़न (Weight)</th>
                                    <th>Piece (पीस ) </th>
                                    <th>पर्चेज कीमत</th>
                                    <th>सेल  कीमत</th>
                                    <th>ऐक्शन</th>
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
                    url: '{{ route('product.getData') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'weight', name: 'weight'},
                    {data: 'in_stock', name: 'in_stock'},
                    {data: 'purchase_price', name: 'purchase_price'},
                    {data: 'sale_price', name: 'sale_price'},
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
                        url: '{{ url('admin/product') }}/' + id,
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
    </script>
@endpush




