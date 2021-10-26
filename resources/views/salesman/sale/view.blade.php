@extends('salesman.layout.app')
@section('title', 'Sale View')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <!-- DataTables -->
    <link href="{{asset('theme/vendors/bower_components/datatables.net-dt/css/jquery.dataTables.min.css')}}"
          rel="stylesheet">
    <link href="{{asset('theme/datatables/responsive.bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>

@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="block-header m-t-20 m-l-5">
                    <div class="col-sm-4">
                        <div class="block-header m-t-5 ">
                            <h2>Sale View</h2>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="alert alert-info" role="alert">Total Amount : {{$sale_total_amount}}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="alert alert-success" role="alert">Paid Amount : {{$pay_amount}}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="alert alert-danger" role="alert">Due Amount : {{$sale_total_amount-$pay_amount}}
                        </div>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-1">
                        <div class="btn-group pull-right ">
                            <a class="btn btn-primary btn-block" href="{{route('salesmansale.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> Back</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header ch-alt">
                            <h2>{{strtoupper($sale->customer_name)}}</h2>
                        </div>
                        <div class="card-body card-padding">
                            <div class="pmo-contact">
                                <ul>
                                    <li class="ng-binding"> Sale
                                        No:- {{$sale->sale_no}} </li>
                                    <li class="ng-binding"> Sale
                                        Date:- {{date('d-M-Y',strtotime($sale->sale_date))}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <ul class="tab-nav tn-justified tn-icon" role="tablist">
                                <li role="presentation" class="active">
                                    <a class="col-sx-4" href="#tab-1" aria-controls="tab-1" role="tab"
                                       data-toggle="tab">
                                        Product
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a class="col-xs-4" href="#tab-2" aria-controls="tab-2" role="tab"
                                       data-toggle="tab">
                                        Payment
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-20">
                                <div role="tabpanel" class="tab-pane animated fadeIn in active" id="tab-1">
                                    <div class="btn-group pull-right">
                                        <a class="btn btn-primary btn-block" data-toggle="modal" href="#addproduct"><i
                                                class="zmdi zmdi-plus"> </i> Add Product</a>
                                    </div>
                                    <div class="row">
                                        <br><br><br>
                                        <div class="table-responsive">
                                            <table id="producttable"
                                                   class="table table-striped table-bordered dt-responsive nowrap"
                                                   cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Total Price</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($sale_product as $row)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$row->product_name}}</td>
                                                        <td>{{$row->qty}} &nbsp{{$row->unite}}</td>
                                                        <td>{{$row->price}}</td>
                                                        <td>{{$row->total_price}}</td>

                                                        <td>

                                                            <button type="button"
                                                                    class="btn btn-primary btn-sm"
                                                                    onclick="model({{$row->id}})"
                                                                    data-toggle="modal"
                                                                    data-target="#editproduct"><i
                                                                    class="zmdi zmdi-edit"></i>
                                                            </button>
                                                            &nbsp

                                                            <a href="{{route('salesmansaleviewproductDelete',$row->id)}}"
                                                               class="btn btn-danger btn-sm"
                                                               onclick="if(!confirm('Are you sure you want to delete this Product?')) return false;"
                                                               type="button"><i class="zmdi zmdi-delete"></i></a>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpane1" class="tab-pane animated fadeIn " id="tab-2">
                                    <div class="btn-group pull-right">
                                        <a class="btn btn-primary btn-block" data-toggle="modal"
                                           href="#addpayment"><i
                                                class="zmdi zmdi-plus"> </i> Add Payment</a>
                                    </div>
                                    <div class="row">
                                        <br><br><br>
                                        <div class="table-responsive">
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-0">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Pay Mode</th>
                                                        <th>Paid</th>
                                                        <th>Pay Date</th>
                                                        <th>Remark</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        @foreach($sale_pay as $row)

                                                            <td>{{$loop->iteration}}</td>
                                                            @if($row->paymode == 0)
                                                                <td>Cash</td>
                                                            @elseif($row->paymode == 1)
                                                                <td>Account</td>
                                                            @elseif($row->paymode == 2)
                                                                <td>Paytm</td>
                                                            @elseif($row->paymode == 3)
                                                                <td>Phone Pay</td>
                                                            @elseif($row->paymode == 4)
                                                                <td>Google Pay</td>
                                                            @else
                                                                <td>Adhar Card</td>
                                                            @endif
                                                            <td>{{$row->pay_amount}}</td>
                                                            <td>{{date('d-M-Y',strtotime($row->pay_date))}}</td>
                                                            <td>{{$row->remark}}</td>

                                                            <td>

                                                                <button type="button"
                                                                        class="btn btn-primary btn-sm"
                                                                        onclick="paymentmodel({{$row->id}})"
                                                                        data-toggle="modal"
                                                                        data-target="#editpayment"><i
                                                                        class="zmdi zmdi-edit"></i>
                                                                </button>
                                                                &nbsp
                                                                <a href="{{route('paymentReceipts',$row->id)}}"
                                                                   class="btn btn-success btn-sm"
                                                                   type="button"><i class="zmdi zmdi-print"></i></a>
                                                                &nbsp

                                                                <a href="{{route('salesmandeletepayment',$row->id)}}"
                                                                   class="btn btn-danger btn-sm"
                                                                   onclick="if(!confirm('Are you sure you want to delete this Payment?')) return false;"
                                                                   type="button"><i class="zmdi zmdi-delete"></i></a>
                                                            </td>
                                                    </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-b-10">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-float bgm-red m-btn" href="{{route('salesmansale.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"></i></a>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Modal For Add product -->
    <div class="modal fade" id="addproduct" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Product</h4>
                </div>
                {!! Form::open(['route' => 'salesmanSaleProductStore','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="saleId" value="{{$sale->id}}">
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="product_id">Product Name <span style="color: red">* </span></label>
                                {!! Form::select('product_id', [''=>'Select Product' ] + $product,old('product_id'), ['required','id'=>'product_id' ,'class'=>'selectpicker productselect' ,'data-dropup-auto'=>"false" ,'data-live-search'=>"true"]) !!}
                                <div style="color: red">{{$errors->first('product_id')}}</div>
                            </div>
                        </div>

                        <div class="col-sm-3 ">
                            <div class="form-group fg-line">
                                <label for="value">Quantity <span style="color: red">* </span></label>
                                <input type="text" name="qty" id="qty" value="{{old('qty')}}"
                                       required="" class="form-control number qty">
                                <div style="color: red">{{$errors->first('qty')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-3 ">
                            <div class="form-group fg-line">
                                <label for="unite">Unit <span style="color: red">* </span></label>
                                <input type="text" name="unite"
                                       id="unite" readonly
                                       class="form-control ">
                                <div style="color: red">{{$errors->first('unite')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="value">Price <span style="color: red">* </span></label>
                                <input type="text" name="price" id="price" value="{{old('price')}}"
                                       required="" class="form-control floatnumber">
                                <div style="color: red">{{$errors->first('price')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="total_price">Total Price <span style="color: red">* </span></label>
                                <input type="text" name="total_price" id="total_price" value="{{old('total_price')}}"
                                       required="" class="form-control floatnumber total_price">
                                <div style="color: red">{{$errors->first('total_price')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{--model for edit product--}}
    <div class="modal fade" id="editproduct" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Product</h4>
                </div>
                {!! Form::open(['route' =>'salesmansaleproductupdate','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="saleId" value="" class="dt">
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="product_id">Product Name <span style="color: red">* </span></label>
                                {!! Form::select('product_id', [''=>'Select Product' ] + $product,old('product_id'), ['required','id'=>'product_id1' ,'class'=>'selectpicker productselect' ,'data-dropup-auto'=>"false",'data-live-search'=>"true" ]) !!}
                                <div style="color: red">{{$errors->first('product_id')}}</div>
                            </div>
                        </div>

                        <div class="col-sm-3 ">
                            <div class="form-group fg-line">
                                <label for="value">Quantity <span style="color: red">* </span></label>
                                <input type="text" name="qty" id="qty1" value="{{old('qty')}}"
                                       required="" class="form-control number qty">
                                <div style="color: red">{{$errors->first('qty')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-3 ">
                            <div class="form-group fg-line">
                                <label for="unite">Unit <span style="color: red">* </span></label>
                                <input type="text" name="unite"
                                       id="unite1" readonly
                                       class="form-control ">
                                <div style="color: red">{{$errors->first('unite')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="value">Price <span style="color: red">* </span></label>
                                <input type="text" name="price" id="price1" value="{{old('price')}}"
                                       required="" class="form-control floatnumber">
                                <div style="color: red">{{$errors->first('price')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="total_price">Total Price <span style="color: red">* </span></label>
                                <input type="text" name="total_price" id="total_price1" value="{{old('total_price')}}"
                                       required="" class="form-control floatnumber total_price">
                                <div style="color: red">{{$errors->first('total_price')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{--model for add Payment--}}
    <div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Payment</h4>
                </div>
                {!! Form::open(['route' => 'salesmanaddpayment','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="sale_id" value="{{$sale->id}}">
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="text">Payment Mode <span class="text-danger">*</span></label>
                                {!! Form::select("paymode", [''=>'Select Payment Mode','0' => 'Cash','1' => 'Account', '2' => 'Paytm', '3' => 'Phone Pay', '4' => 'Google Pay','5'=>'Adhar Card'], 'old("paymode")', ['class'=>'selectpicker ', 'id'=>'paymode' ,'required','data-dropup-auto'=>"false",'data-live-search'=>"true" ]  ) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="pay_amount">Pay Amount <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="payamount"
                                       class="form-control floatnumber "
                                       max="200"
                                       name="pay_amount" required
                                       value="{{old('pay_amount')}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="pay_date">Pay Date <span class="text-danger">*</span></label>
                                <input type='text'
                                       class="form-control created_date  isRequred"
                                       name="pay_date" id="paydate"
                                       value="{{date('d-M-Y')}}"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="remark">Remark</label>
                                <input type='text' class="form-control"
                                       name="remark" id="remark"
                                       value="{{old('remark')}}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    {{--model for Update Payment--}}
    <div class="modal fade" id="editpayment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Payment</h4>
                </div>
                {!! Form::open(['route' => 'salesmanupdatepayment','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="pay_id" value="" class="dts">
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="text">Payment Mode <span class="text-danger">*</span></label>
                                {!! Form::select("paymode", [''=>'Select Payment Mode','0' => 'Cash','1' => 'Account', '2' => 'Paytm', '3' => 'Phone Pay', '4' => 'Google Pay','5'=>'Adhar Card'], old("paymode"), ['id'=>'paymode1','class'=>'selectpicker productselect' ,'required','data-dropup-auto'=>"false" ,'data-live-search'=>"true"]  ) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="pay_amount">Pay Amount <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="pay_amount1"
                                       class="form-control floatnumber "
                                       max="200"
                                       name="pay_amount" required
                                       value="{{old('pay_amount')}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="pay_date">Pay Date <span class="text-danger">*</span></label>
                                <input type='text' class="form-control created_date "
                                       name="pay_date" id="pay_date1" required
                                       value="{{date('d-m-Y'),old('pay_date')}}"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="remark">Remark</label>
                                <input type='text' class="form-control"
                                       name="remark" id="remark1"
                                       value="{{old('remark')}}"/>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
@push('footerscript')
    <script
        src="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('theme/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('theme/datatables/dataTables.responsive.min.js')}}"></script>
    <script>
        $(function () {
            $('#producttable').DataTable({
                processing: true,
            });
        });
        $(function () {
            $('#paytable').DataTable({
                processing: true,
            });
        });
        $('.number').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
            }
        });
        $('.floatnumber').on('input', function () {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        });
        $('.created_date').datetimepicker({
            format: "DD-MMM-YYYY"
        });
    </script>
    <script>
        /*product create*/
        $("#product_id").on('change', function () {
            var productId = $(this).val();
            $.ajax({
                'url': "{{route('salesmanproduct.getProductData')}}",
                type: 'get',
                data: {'product_id': productId},
                success: function (response) {
                    $("#price").val(response.sale_price);
                    $("#qty").val("");
                    $("#unite").val(response.unite);
                    $("#total_price").val("");
                }
            });
        });
        $("#qty").on('keyup', function () {
            var qty = $(this).val();
            var price = $("#price").val();
            var total_price = qty * price;
            $('#total_price').val(total_price);

        })
        $("#price").on('keyup', function () {
            var qty = $("#qty").val();
            var price = $(this).val();
            var total_price = price * qty;
            $('#total_price').val(total_price);

        })
        /*product edit */
        function model(e) {
            $('.dt').val(e);
            $.ajax({
                url: "{{url('salesman/salesmansaleproductedit')}}",
                type: 'get',
                data: {'id': e},
                success: function (response) {
                    $("#product_id1").val(response.product_id);
                    $("#qty1").val(response.qty);
                    $("#unite1").val(response.unite);
                    $("#price1").val(response.price);
                    $("#total_price1").val(response.total_price);
                }
            });
        }

        $("#product_id1").on('change', function () {
            var productId = $(this).val();
            $.ajax({
                'url': "{{route('salesmanproduct.getProductData')}}",
                type: 'get',
                data: {'product_id': productId},
                success: function (response) {
                    $("#price1").val(response.sale_price);
                    $("#qty1").val("");
                    $("#unite1").val(response.unite);
                    $("#total_price1").val("");
                }
            });

        });

        $("#qty1").on('keyup', function () {
            var qty = $(this).val();
            var price = $("#price1").val();
            var total_price = qty * price;
            $('#total_price1').val(total_price);

        })

        $("#price1").on('keyup', function () {
            var qty = $("#qty1").val();
            var price = $(this).val();
            var total_price = price * qty;
            $('#total_price1').val(total_price);

        })
        //update payment
        function paymentmodel(e) {
            $('.dts').val(e);
            $.ajax({
                url: "{{url('salesman/editpayment')}}",
                type: 'get',
                dateType: 'json',
                data: {'id': e},
                success: function (response) {
                    var data = response.data;
                    $("#paymode1").val(data.paymode);
                    $("#pay_amount1").val(data.pay_amount);
                    $("#pay_date1").val(response.pay_date);
                    $("#remark1").val(data.remark);
                }
            });
        }
    </script>
@endpush





