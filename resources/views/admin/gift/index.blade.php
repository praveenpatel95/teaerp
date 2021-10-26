@extends('admin.layout.app')
@section('title', 'गिफ्ट असाइन ग्राहक लिस्ट')
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
                                    class="zmdi zmdi-settings"> </i> गिफ्ट सेटिंग
                            </a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2 >गिफ्ट असाइन ग्राहक लिस्ट </h2>
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
                                    <th>ग्राहक का नाम </th>
                                    <th>गिफ्ट प्रोडक्ट </th>
                                    <th>क़ीमत </th>
                                    <th>गिफ्ट दिनांक </th>
                                    <th>स्थिति</th>
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
                    <h4 class="modal-title">अपडेट गिफ्ट प्रोडक्ट</h4>
                </div>
                <br>
                {!! Form::open(['route' => 'giftProduct.update','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="product_name">गिफ्ट प्रोडक्ट </label>
                                <input type="text" name="product_name"
                                       value="{{old('product_name',$gift_product->product_name)}}" class="form-control">
                                <div style="color: red">{{$errors->first('product_name')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="product_name">क़ीमत </label>
                                <input type="text" name="price"
                                       value="{{old('price',$gift_product->price)}}" class="form-control">
                                <div style="color: red">{{$errors->first('price')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="gift_qty">गिफ्ट मात्रा सेट करें  </label>
                                <input type="text" name="gift_qty"
                                       value="{{old('gift_qty',$gift_product->gift_qty)}}" class="form-control">
                                <div style="color: red">{{$errors->first('gift_qty')}}</div>
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
                    url: '{{ route('giftCustomer.getData') }}',
                },

                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();

                    $(api.column(3).footer()).html(json.total_gift_amount ).css('color', 'red');
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'gift_product', name: 'gift_product'},
                    {data: 'price', name: 'price'},
                    {data: 'gift_date', name: 'gift_date'},
                    {data: 'status', name: 'status'},
                ]
            });
        });
    </script>
@endpush
