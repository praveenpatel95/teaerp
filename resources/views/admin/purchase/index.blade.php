@extends('admin.layout.app')
@section('title', 'पर्चेज लिस्ट')
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
                            <a class="btn btn-primary btn-block" href="{{route('purchase.create')}}"><i class="zmdi zmdi-plus"></i> पर्चेज जोड़ें</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2 >पर्चेज लिस्ट</h2>
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
                                    <th>प्रोडक्ट </th>
                                    <th>मात्रा</th>
                                    <th>पर्चेज कीमत</th>
                                    <th>पर्चेज तिथी </th>
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
                    url: '{{ route('purchase.getData') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'product_name', name: 'product_name'},
                    {data: 'qty', name: 'qty'},
                    {data: 'purchase_price', name: 'purchase_price'},
                    {data: 'purchase_date', name: 'purchase_date'},
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
                        url: '{{ url('admin/purchase') }}/' + id,
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




