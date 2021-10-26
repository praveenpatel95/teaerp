@extends('admin.layout.app')
@section('title', 'सेल ब्याज ग्राहक लिस्ट')
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
                            <a class="btn btn-info btn-block" href="#giftsetting" data-toggle="modal"><i
                                    class="zmdi zmdi-settings"> </i> सेल ब्याज सेटिंग
                            </a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2 >सेल ब्याज ग्राहक लिस्ट </h2>
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
                                    <th>सेल न.</th>
                                    <th>ग्राहक का नाम </th>
                                    <th>राशि </th>
                                    <th>प्रतिशत </th>
                                    <th>ब्याज राशि</th>
                                    <th>दिनांक</th>
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
    <!-- Modal For Gift Setting -->
    <div class="modal fade" id="giftsetting" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">अपडेट सेल ब्याज सेटिंग</h4>
                </div>
                <br>
                {!! Form::open(['route' => 'saleInterestSetting.update','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="product_name">दिन </label>
                                <input type="text" name="days"
                                       value="{{old('days',$data->days)}}" class="form-control">
                                <div style="color: red">{{$errors->first('days')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="percent">प्रतिशत </label>
                                <input type="text" name="percent" id="percent"
                                       value="{{old('percent',$data->percent)}}" class="form-control">
                                <div style="color: red">{{$errors->first('percent')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" id="submitBtn" class="btn btn-primary ">सेव</button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
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
                    url: '{{route('SaleInterest.getData')}}',
                },

                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(api.column(5).footer()).html(json.interest).css('color', 'red');
                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'sale_no', name: 'sale_no'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'percentage', name: 'percentage'},
                    {data: 'interest_amount', name: 'interest_amount'},
                    {data: 'interest_date', name: 'interest_date'},
                ]
            });
        });
    </script>
@endpush
