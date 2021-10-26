@extends('admin.layout.app')
@section('title', 'लाइन लिस्ट')
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
                            <a class="btn btn-primary btn-block" href="{{route('line.create')}}"><i
                                    class="zmdi zmdi-plus"></i> लाइन जोड़े</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>लाइन लिस्ट</h2>
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
                                    <th>लाइन का नाम</th>
                                    <th >ऐक्शन</th>
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
                    url: '{{ route('line.getData') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'line_name', name: 'line_name'},
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
                        url: '{{ url('admin/line') }}/' + id,
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




