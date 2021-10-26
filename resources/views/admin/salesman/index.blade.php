@extends('admin.layout.app')
@section('title', 'सेल्समेन लिस्ट')
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
                            <a class="btn btn-primary btn-block" href="{{route('salesman.create')}}"><i class="zmdi zmdi-plus"></i> सेल्समेन
                                जोड़ें</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2 >सेल्समेन लिस्ट</h2>
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
                                    <th>नाम</th>
                                    <th>पिता का नाम</th>
                                    <th>मोबाइल नंबर</th>
                                    <th>लाइन</th>
                                    <th>जोइनिंग तारीख़</th>
                                    <th>वेतन राशि</th>
                                    <th>स्थिति</th>
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
                    url: '{{ route('salesman.getData') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'line_name', name: 'line_name'},
                    {data: 'joining_date', name: 'joining_date'},
                    {data: 'salary_amount', name: 'salary_amount'},
                    {data: 'status', name: 'status'},
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
                        url: '{{ url('admin/salesman') }}/' + id,
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




