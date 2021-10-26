@extends('salesman.layout.app')
@section('title', 'Add Sale')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <style>
        .tdwidth{
            width: 94% !important;
            margin: 2px !important;

        }
        .tdselect{
            width: 98% !important;
        }
    </style>
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('salesmansale.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> Back</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>Add Sale</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>'salesmansale.store','method'=>'post','onsubmit'=>'submitBtn.disabled = true','return'=>true ]) !!}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block-header m-t-20 ">
                                        <h2>Basic Detail</h2>
                                    </div>
                                </div>
                                <div class="col-sm-4 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_id">Customer <span style="color: red">* </span></label>
                                        {!! Form::select('customer_id', [''=>'Select']+$customer, old('customer_id'), ['required','id'=>'customer_id'  , 'class'=>'selectpicker' ,'data-live-search'=> 'true' ,'data-dropup-auto'=>"false" ]) !!}
                                        <div style="color: red">{{$errors->first('customer_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group fg-line">
                                        <label for="rec_no"> Sale No. <span style="color: red">* </span></label>
                                        <input type="text" name="sale_no" id="sale_no" class="form-control " readonly
                                               required="" value="{{old('sale_no',$sale_no)}}">
                                        <div style="color: red">{{$errors->first('sale_no')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group fg-line">
                                        <label for="sale_date">Sale Date <span
                                                style="color: red">* </span></label>
                                        <input type="text" id="sale_date"
                                               name="sale_date" class="form-control date-picker created_date"
                                               required="" value="{{old('sale_date',date('d-M-Y'))}}">
                                        <div style="color: red">{{$errors->first('sale_date')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="block-header m-t-20 ">
                                        <h2>Sale Product</h2>
                                    </div>
                                </div>
                                {{--Sale Product--}}
                                <div class="col-sm-12">
                                    <table id="product_table" >
                                        <thead>
                                        <tr class="text-center">
                                            <th width="30%">Product<span
                                                    class="text-danger">*</span>
                                            </th>
                                            <th width="10%">Qty <span class="text-danger">*</span></th>
                                            <th width="14%">Unit</th>
                                            <th>Price<span class="text-danger">*</span></th>
                                            <th>Total Price<span class="text-danger">*</span></th>
                                            <th>
                                                <button type="button"
                                                        class="btn btn-primary tdwidth"
                                                        id="addrow">
                                                    <i class="zmdi zmdi-plus"></i>
                                                </button>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td >
                                                {!! Form::select('product_id[]', [''=>'Select Product' ] + $product,old('product_id'), ['required','id'=>'product_id' ,'class'=>'form-control productselect tdselect' ,'data-dropup-auto'=>"false"  ]) !!}
                                            </td>

                                            <td>
                                                <input type="text" name="qty[]"
                                                       id="qty" required=""
                                                       class="form-control qty floatnumber tdwidth">
                                            </td>
                                            <td>
                                                <input type="text" name="unite[]"
                                                       id="unite" readonly
                                                       class="form-control tdwidth ">
                                            </td>
                                            <td>
                                                <input type="text" name="price[]"
                                                       id="price" required=""
                                                       class="form-control price floatnumber tdwidth">
                                            </td>
                                            <td>
                                                <input type="text" name="total_price[]"
                                                       id="total_price" required=""
                                                       class="form-control total_price floatnumber tdwidth">
                                            </td>
                                            <td>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4 ">
                                    <br>
                                    <div class="form-group fg-line">
                                        <label for="total_amount">Total Amount <span
                                                style="color: red">* </span></label>
                                        <input type="text" name="total_amount" id="total_amount"
                                               class="form-control total_amount floatnumber" readonly
                                               required="" value="{{old('total_amount')}}">
                                        <div style="color: red">{{$errors->first('total_amount')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="checkbox">
                                        <label class="block-header m-t-20 ">
                                            <input type="checkbox" id="payment">
                                            <i class="input-helper"></i><b>
                                                MAKE PAYMENT</b>
                                        </label>
                                        <br>
                                    </div>
                                </div>
                                {{--payment--}}
                                <div style="display:none " id="addpayment">
                                    <div class="col-sm-6">
                                        <div class="form-group fg-line">
                                            <label for="text">Payment Mode <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select("paymode", [''=>'Select Payment Mode','1' => 'Cash','2' => 'Account', '3' => 'Paytm', '4' => 'Phone Pay', '5' => 'Google Pay','6'=>'Adhar Card'], 'old("payment_mode")', ['class'=>'selectpicker isRequred', 'id'=>'paymode' ,'data-dropup-auto'=>"false",'data-live-search'=>"true"  ]  ) !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group fg-line">
                                            <label for="pay_amount">Pay Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="payamount"
                                                   class="form-control floatnumber isRequred"
                                                   max="200"
                                                   name="pay_amount"
                                                   value="{{old('pay_amount')}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group fg-line">
                                            <label for="dueamount">Due Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="dueamount"
                                                   class="form-control floatnumber "
                                                   max="200" readonly
                                                   name="dueamount"
                                                   value="{{old('dueamount')}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group fg-line">
                                            <label for="pay_date">Pay Date <span
                                                    class="text-danger">*</span></label>
                                            <input type='text'
                                                   class="form-control created_date  isRequred"
                                                   name="pay_date" id="paydate"
                                                   value="{{date('d-M-Y')}}"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group fg-line">
                                            <label for="remark">Remark</label>
                                            <input type='text' class="form-control"
                                                   name="remark" id="remark"
                                                   value="{{old('remark')}}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <button type="submit" id="submitBtn" class="btn btn-primary ">Submit</button>
                                        &nbsp&nbsp&nbsp
                                        <button type="reset" class="btn btn-default ">Reset</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
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
@endsection
@push('footerscript')
    <script
        src="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
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
                    getTotal();
                }
            });

        });
        $("#qty").on('keyup', function () {
            var qty = $(this).val();
            var price = $("#price").val();
            var total_price = qty * price;
            $('#total_price').val(total_price);
            getTotal()
        })
        $("#price").on('keyup', function () {
            var qty = $("#qty").val();
            var price = $(this).val();
            var total_price = price * qty;
            $('#total_price').val(total_price);
            getTotal()
        })
        function getTotal() {
            var total_price = 0;
            $(".total_price").each(function () {
                total_price += +$(this).val();
            });
            $("#total_amount").val(total_price)
            var payamount = $("#payamount").val()
            var due = total_price - payamount;
            $("#dueamount").val(due)
        }
        $("#total_price,#payamount").on('keyup', function () {
            getTotal()
        });
        $('#payment').on('click', function () {
            $('#addpayment').toggle();
            if ($("#payamount,#paydate,#paymode").attr('required')) {
                $(".isRequred").prop('required', false);
            } else {
                $(".isRequred").prop('required', true);
            }
        });
        $('#payamount').on('input', function () {

            var value = $(this).val();
            var total_amount = $('#total_amount').val();

            if ((value !== '') && (value.indexOf('.') === -1)) {

                $(this).val(Math.max(Math.min(value, total_amount)));
            }
        });
    </script>

    <script>
        /*Add Multiple Product*/
        $(document).ready(function () {
            var i = 2;
            $("#addrow").on('click', function () {
                var col = "<tr>";

                col += '<td><select  name="product_id[]" class="form-control productselect tdselect"  id="product_id' + i + '"  required="">' +
                    '<option value=""> Select Product </option>@foreach($product as $row => $value)<option value="{{$row}}">{{$value}}</option>@endforeach
                        </select></td>';
                col += '<td><input type="text" class="form-control qty number tdwidth " name="qty[]" id="qty' + i + '"></td>';
                col += '<td><input type="text" class="form-control tdwidth " readonly name="qty[]" id="unite' + i + '"></td>';
                col += '<td><input type="text" class="form-control price floatnumber tdwidth " name="price[]" id="price' + i + '" ></td>';
                col += '<td><input type="text" class="form-control total_price floatnumber tdwidth " name="total_price[]" id="total_price' + i + '" ></td>';
                col += '<td><button class="btn btn-danger tdwidth" id="deleteRow" type="button"><i class="zmdi zmdi-delete"></i></button></td>';
                $("table#product_table").append(col);
                i++;

                $("select[id^=product_id]").on('change', function () {
                    var lastnum = $(this).attr('id').slice(-1);
                    var productId = $(this).val();
                    $.ajax({
                        'url': "{{route('salesmanproduct.getProductData')}}",
                        type: 'get',
                        data: {'product_id': productId},
                        success: function (response) {
                            $("#price" + lastnum).val(response.sale_price);
                            $("#unite" + lastnum).val(response.unite);
                            $("#qty" + lastnum).val("");
                            $("#total_price" + lastnum).val("");
                            getTotal();

                        }
                    });
                });

                $(".qty").on('keyup', function () {
                    var lastnum = $(this).attr('id').slice(-1);
                    var qty = $(this).val();
                    var price = $("#price" + lastnum).val();
                    var total_price = qty * price;
                    $("#total_price" + lastnum).val(total_price);
                    getTotal();

                })

                $(".price").on('keyup', function () {
                    var lastnum = $(this).attr('id').slice(-1);
                    var qty = $("#qty" + lastnum).val();
                    var price = $(this).val();
                    var total_price = price * qty;
                    $("#total_price" + lastnum).val(total_price);
                    getTotal();

                })

                /*Change on Total Price*/
                $(".total_price").on('keyup', function () {
                    getTotal()
                });

                $("table#product_table").on('click', function () {
                    getTotal()
                });

                $("table#product_table").on("click", "#deleteRow", function (event) {
                    $(this).closest("tr").remove();
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

            });
        });
    </script>
@endpush



