@extends('admin.layout.app')
@section('title', ' सेल जोड़ें')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
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
                            <h2> सेल जोड़ें</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>'sale.store','method'=>'post', ]) !!}
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="block-header m-t-20 ">
                                        <h2>बेसिक डिटेल</h2>
                                    </div>
                                </div>
                                <div class="col-sm-4" id="giftData_id">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_id"> ग्राहक <span style="color: red">* </span></label>
                                        {!! Form::select('customer_id', [''=>'ग्राहक चुनें']+$customer, old('customer_id'), ['required','id'=>'customer_id'  , 'class'=>'selectpicker' ,'data-live-search'=> 'true' ,'data-dropup-auto'=>"false" ]) !!}
                                        <div style="color: red">{{$errors->first('customer_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_id">सेल्समेन <span style="color: red">* </span></label>
                                        {!! Form::select('salesman_id', [''=>'सेल्समेन चुनें']+$salesman, old('salesman_id'), ['required','id'=>'salesman_id'  , 'class'=>'selectpicker' ,'data-live-search'=> 'true','data-dropup-auto'=>"false"  ]) !!}
                                        <div style="color: red">{{$errors->first('salesman_id')}}</div>
                                    </div>
                                </div>
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
                                               required="" value="{{old('sale_date',date('d-M-Y'))}}">
                                        <div style="color: red">{{$errors->first('sale_date')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group fg-line">
                                        <label for="old_due">पुराना बकाया राशि </label>
                                        <input type="text" class="form-control " readonly
                                               value="" id="old_due">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block-header m-t-20 ">
                                        <h2>सेल प्रोडक्ट</h2>
                                    </div>
                                </div>
                            </div>
                            {{--Sale Product--}}
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label>प्रोडक्ट <span
                                                class="text-danger">*</span>
                                        </label>
                                        {!! Form::select('product_id', [''=>'प्रोडक्ट चुनें' ] + $product,old('product_id'), ['required','id'=>'product_id', 'class'=>'selectpicker' ,'data-live-search'=> 'true','data-dropup-auto'=>"false"  ]) !!}
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label width="10%">मात्रा <span class="text-danger">*</span></label>
                                        <input type="text" name="qty"
                                               id="qty" required=""
                                               class="form-control qty floatnumber">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label>कीमत<span class="text-danger">*</span></label>
                                        <input type="text" name="price"
                                               id="price" required=""
                                               class="form-control price floatnumber ">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label>कुल कीमत<span class="text-danger">*</span></label>
                                        <input type="text" name="total_price"
                                               id="total_price" required=""
                                               class="form-control total_price floatnumber">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label width="10%"> रिटर्न तारीख़ </label>
                                        <input type="text" id="return_date"
                                               name="return_date" class="form-control date-picker return_date"
                                               value="{{old('return_date',date('d-M-Y'))}}">
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
                            {{--payment--}}
                            <div class="row">
                                <div style="display:none " id="addpayment">
                                    <div class="col-sm-6">
                                        <div class="form-group fg-line">
                                            <label for="text">पेमेंट का तरीका <span
                                                    class="text-danger">*</span></label>
                                            {!! Form::select("paymode", [''=>'पेमेंट का तरीका चुनें ','1' => 'Cash','2' => 'Account', '3' => 'Paytm', '4' => 'Phone Pay', '5' => 'Google Pay','6'=>'Adhar Card'], 'old("payment_mode")', ['class'=>'selectpicker isRequred', 'id'=>'paymode','data-dropup-auto'=>"false" ]  ) !!}
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
                                            <label for="dueamount">डयू राशि<span
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
                                                   class="form-control created_date date-picker  isRequred"
                                                   name="pay_date" id="paydate"
                                                   value="{{date('d-M-Y')}}"/>
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
            format: "DD-MMM-YYYY",
        });
        $('.return_date').datetimepicker({
            format: "DD-MMM-YYYY",
            setDate : null
        });
    </script>
    <script>
       function CheckFunction(){
           var customer_id = $('#customer_id').val();
            var status_id = $('#giftStatus').val();

            $.ajax({
               'url': "{{route('giftStatus.update')}}",
               type: 'get',
               data: {'customer_id': customer_id,'status_id':status_id},
               success: function () {
                   location.reload();

               }
           });
        }

        //get old due amount
        $("#customer_id").on('change', function () {
            var customerId = $(this).val();
            $.ajax({
                'url': "{{route('Customer.getOldDue')}}",
                type: 'get',
                data: {'customer_id': customerId},
                success: function (response) {
                    $("#old_due").val(response.due_balance);
                }
            });
        });

        //get gift status
        $("#customer_id").on('change', function () {
            var customerId = $(this).val();

            $.ajax({
                'url': "{{route('Customer.getGiftData')}}",
                type: 'get',
                data: {'customer_id': customerId},
                success: function (result) {
                    $("#giftData_id").html(result);
                }
            });
        });

        $("#product_id").on('change', function () {
            var productId = $(this).val();
            $.ajax({
                'url': "{{route('product.getProductData')}}",
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
            $(".total_price").val(total_price)
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
            var total_amount = $('#total_price').val();

            if ((value !== '') && (value.indexOf('.') === -1)) {

                $(this).val(Math.max(Math.min(value, total_amount)));
            }

        });
    </script>
@endpush



