@extends('admin.layout.app')
@section('title', ' सेल जोड़ें')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <style>
        .table > tbody > tr > td:first-child, .table > tbody > tr > th:first-child, .table > tfoot > tr > td:first-child, .table > tfoot > tr > th:first-child, .table > thead > tr > td:first-child, .table > thead > tr > th:first-child {
            padding-left: 5px !important;
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
                            <a class="btn btn-primary btn-block" href="{{route('sale.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>सेल जोड़ें</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>'sale.store','method'=>'post','onsubmit'=>'submitBtn.disabled = true','return'=>true ]) !!}
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="block-header m-t-20 ">
                                        <h2>बेसिक डिटेल</h2>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    @if($gift_data && $gift_data->gift_status == 0)
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h5 style="color: red">* गिफ्ट {{$gift_data->gift_product}}</h5>
                                            </div>
                                            <div class="col-sm-6">

                                                <label class="checkbox checkbox-inline m-r-20 m-t-10">
                                                    {{$gift_data->status}}
                                                    <input type="checkbox"
                                                           {{--{{$gift_data->gift_status == 1 ? 'checked' : ''}}--}} value="1"
                                                           onclick="CheckFunction()" id="giftStatus">
                                                    <i class="input-helper"></i>
                                                    अप्रूव करे
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label for="customer_id">ग्राहक<span style="color: red">* </span></label>
                                        <input type="text" value="{{$data->customer_name}}" readonly
                                               class="form-control" name="general">
                                        <div style="color: red">{{$errors->first('customer_name')}}</div>
                                        <input type="hidden" id="customer_id" value="{{$data->id}}">
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="salesman">सेल्समेन <span style="color: red">* </span></label>

                                        {!! Form::select('salesman_id', [''=>'चुने']+$salesmans, $salesman->id, ['required','id'=>'route_id'  , 'class'=>'selectpicker' ,'data-live-search'=> 'true','data-dropup-auto'=>"false" ,'data-live-search'=>'true']) !!}
                                        <div style="color: red">{{$errors->first('salesman')}}</div>

                                    </div>
                                </div>
                                <input type="hidden" value="{{$data->id}}" name="customer_id">
                                <div class="col-sm-2">
                                    <div class="form-group fg-line">
                                        <label for="rec_no"> सेल न. <span style="color: red">* </span></label>
                                        <input type="text" name="sale_no" id="sale_no" class="form-control " readonly
                                               required="" value="{{old('sale_no',$sale_no)}}">
                                        <div style="color: red">{{$errors->first('sale_no')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group fg-line">
                                        <label for="sale_date">सेल की तिथी <span
                                                style="color: red">* </span></label>
                                        <input type="text" id="sale_date"
                                               name="sale_date" class="form-control date-picker created_date"
                                               required="" value="{{old('sale_date',date('d-M-Y H:i:s'))}}">
                                        <div style="color: red">{{$errors->first('sale_date')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group fg-line">
                                        <label for="old_due">पुराना बकाया राशि </label>
                                        <input type="text" id="old_due" class="form-control " readonly
                                               value="{{$data->due_balance}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block-header m-t-20 ">
                                        <h2>सेल प्रोडक्ट</h2>
                                    </div>
                                </div>
                                {{--Sale Product--}}
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="table-responsive">
                                            <table id="product_table" class="table table-striped">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>प्रोडक्ट<span
                                                            class="text-danger">*</span>
                                                    </th>
                                                    <th width="10%">मात्रा <span class="text-danger">*</span></th>
                                                    <th>कीमत<span class="text-danger">*</span></th>
                                                    <th>कुल कीमत<span class="text-danger">*</span></th>
                                                    <th>
                                                        <button type="button"
                                                                class="btn btn-primary"
                                                                id="addrow">
                                                            <i class="zmdi zmdi-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        {!! Form::select('product_id[]', [''=>'प्रोडक्ट चुनें' ] + $product,old('product_id'), ['required','id'=>'product_id' ,'class'=>'form-control productselect ']) !!}
                                                    </td>
                                                    <td>
                                                        <input type="text" name="qty[]"
                                                               id="qty" required=""
                                                               class="form-control qty floatnumber">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="price[]"
                                                               id="price" required=""
                                                               class="form-control price floatnumber ">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total_price[]"
                                                               id="total_price" required=""
                                                               class="form-control total_price floatnumber ">
                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4 ">
                                    <div class="form-group fg-line">
                                        <label for="total_amount">कुल राशि <span
                                                style="color: red">* </span></label>
                                        <input type="text" name="total_amount" id="total_amount"
                                               class="form-control total_amount floatnumber" readonly
                                               required="" value="{{old('total_amount')}}">
                                        <div style="color: red">{{$errors->first('total_amount')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label width="10%"> रिटर्न तारीख़ </label>
                                        <input type="text" id="return_date"
                                               name="return_date" class="form-control date-picker return_date"
                                               >
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group fg-line">
                                        <label width="10%">रिमार्क </label>
                                        <input type="text" name="remark"
                                               id="remark"
                                               class="form-control ">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="checkbox">
                                        <label class="block-header m-t-20 ">
                                            <input type="checkbox" id="payment">
                                            <i class="input-helper"></i><b>
                                                पेमेंट करे</b>
                                        </label>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{--payment--}}
                                <div style="display:none " id="addpayment">
                                    <div class="col-sm-6">
                                        <div class="form-group fg-line">
                                            <label for="text">पेमेंट का तरीका <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select("paymode", [''=>'पेमेंट का तरीका चुने','1' => 'Cash','2' => 'Account', '3' => 'Paytm', '4' => 'Phone Pay', '5' => 'Google Pay','6'=>'Adhar Card'], 'old("payment_mode")', ['class'=>'selectpicker isRequred', 'id'=>'paymode' ,'data-dropup-auto'=>"false" ,'data-live-search'=>'true']  ) !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group fg-line">
                                            <label for="pay_amount">पे राशि <span
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
                                            <label for="dueamount">डयू राशि <span
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
                                            <label for="pay_date">पेमेंट की तिथि <span
                                                    class="text-danger">*</span></label>
                                            <input type='text'
                                                   class="form-control created_date  isRequred"
                                                   name="pay_date" id="paydate"
                                                   value="{{date('d-M-Y H:i:s')}}"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group fg-line">
                                            <label for="remark">रिमार्क</label>
                                            <input type='text' class="form-control"
                                                   name="remark" id="remark"
                                                   value="{{old('remark')}}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <button type="submit" id="submitBtn" class="btn btn-primary ">सेव</button>
                                        &nbsp&nbsp&nbsp
                                        <button type="reset" class="btn btn-default ">रीसेट</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
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
            format: "DD-MMM-YYYY HH:mm:ss",
        });

        $('.return_date').datetimepicker({
            format: "DD-MMM-YYYY",
        });
    </script>
    <script>
        function CheckFunction() {
            var customer_id = $('#customer_id').val();
            var status_id = $('#giftStatus').val();

            $.ajax({
                'url': "{{route('giftStatus.update')}}",
                type: 'get',
                data: {'customer_id': customer_id, 'status_id': status_id},
                success: function () {
                    location.reload();
                }
            });
        }


        $("#product_id").on('change', function () {
            var productId = $(this).val();
            $.ajax({
                'url': "{{route('product.getProductData')}}",
                type: 'get',
                data: {'product_id': productId},
                success: function (response) {
                    $("#price").val(response.sale_price);
                    $("#qty").val("");
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

                col += '<td><select  name="product_id[]" class="form-control productselect"  id="product_id' + i + '"  required="">' +
                    '<option value=""> प्रोडक्ट चुनें </option>@foreach($product as $row => $value)<option value="{{$row}}">{{$value}}</option>@endforeach
                        </select></td>';
                col += '<td><input type="text" class="form-control qty number " name="qty[]" id="qty' + i + '"></td>';
                col += '<td><input type="text" class="form-control price floatnumber " name="price[]" id="price' + i + '" ></td>';
                col += '<td><input type="text" class="form-control total_price floatnumber " name="total_price[]" id="total_price' + i + '" ></td>';
                col += '<td><button class="btn btn-danger" id="deleteRow" type="button"><i class="zmdi zmdi-delete"></i></button></td>';
                $("table#product_table").append(col);
                i++;

                $("select[id^=product_id]").on('change', function () {
                    var lastnum = $(this).attr('id').slice(-1);
                    var productId = $(this).val();
                    $.ajax({
                        'url': "{{route('product.getProductData')}}",
                        type: 'get',
                        data: {'product_id': productId},
                        success: function (response) {
                            $("#price" + lastnum).val(response.sale_price);
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


            });
        });
    </script>
@endpush



