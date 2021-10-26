@extends('admin.layout.app')
@section('title', 'अपडेट सेल ')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <style>
        .productselect {
            padding-bottom: 5px !important;
        }

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
                            <h2>अपडेट सेल</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>array('sale.update',$sale->id),'method'=>'put', ]) !!}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block-header m-t-20 ">
                                        <h2>बेसिक डिटेल</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_id">ग्राहक <span style="color: red">* </span></label>
                                        <input type="text" class="form-control " readonly name="customer_id"
                                               value="{{old('rec_no',$sale->customer_name)}}">
                                        <input type="hidden" name="sale_id"
                                               value="{{$sale->id}}">
                                        <input type="hidden" name="customer_id"
                                               value="{{$sale->customer_id}}">
                                        <div style="color: red">{{$errors->first('customer_id')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_id">सेल्समेन <span style="color: red">* </span></label>
                                        {!! Form::select('salesman_id', [''=>'Select']+$salesman, $sale->salesman_id, ['required','id'=>'salesman_id'  , 'class'=>'selectpicker' ,'data-live-search'=> 'true' ,'data-dropup-auto'=>"false" ]) !!}
                                        <div style="color: red">{{$errors->first('salesman_id')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label for="rec_no"> सेल न. </label>
                                        <input type="text" name="sale_no" id="sale_no" class="form-control " readonly
                                               value="{{old('rec_no',$sale->sale_no)}}">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label for="sale_date">सेल की तिथी<span
                                                style="color: red">* </span></label>
                                        <input type="text" id="sale_date"
                                               name="sale_date" class="form-control date-picker created_date"
                                               required=""
                                               value="{{old('sale_date',date('d-M-Y H:i:s',strtotime($sale->sale_date)))}}">
                                        <div style="color: red">{{$errors->first('sale_date')}}</div>
                                    </div>
                                </div>
                                {{--<div class="col-sm-2">
                                    <div class="form-group fg-line">
                                        <label for="old_due">पुराना बकाया राशि </label>
                                        <input type="text" name="old_due" id="old_due" class="form-control " readonly
                                               value="{{old('rec_no',$sale->due_balance)}}">
                                    </div>
                                </div>--}}
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block-header m-t-20 ">
                                        <h2>सेल प्रोडक्ट</h2>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="table-responsive">
                                            <table id="product_table" class="table table-striped">
                                                <thead>
                                                <tr class="text-center">
                                                    <th>प्रोडक्ट</th>
                                                    <th width="10%">मात्रा</th>
                                                    <th>कीमत</th>
                                                    <th>कुल कीमत</th>
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
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach($sale_product as $row)
                                                    <tr>
                                                        <td>
                                                            {!! Form::select('product_id[]', [''=>'प्रोडक्ट चुनें' ] + $product,$row->product_id, ['required','id'=>'product_id'.$i ,'class'=>'form-control productselect' ,'data-dropup-auto'=>"false" ]) !!}
                                                        </td>

                                                        <td>
                                                            <input type="text" name="qty[]"
                                                                   id="qty{{$i}}" required="" value="{{$row->qty}}"
                                                                   class="form-control qty floatnumber">
                                                        </td>

                                                        <td>
                                                            <input type="text" name="price[]"
                                                                   id="price{{$i}}" required=""
                                                                   value="{{$row->price}}"
                                                                   class="form-control price floatnumber ">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="total_price[]"
                                                                   id="total_price{{$i}}" required=""
                                                                   value="{{$row->total_price}}"
                                                                   class="form-control total_price floatnumber ">
                                                        </td>
                                                        <td>
                                                            <a href="{{route('saleproduct.delete',$row->id)}}"
                                                               class="btn btn-danger btn-sm"
                                                               onclick="if(!confirm('Are you sure you want to delete this Product?')) return false;"
                                                               type="button"><i class="zmdi zmdi-delete"></i></a>

                                                        </td>
                                                    </tr>
                                                    <input type="hidden" name="checkval[]"
                                                           value="{{$row->id}}">
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4 ">
                                    <br>
                                    <div class="form-group fg-line">
                                        <label for="total_amount">कुल राशि <span
                                                style="color: red">* </span></label>
                                        <input type="text" name="total_amount" id="total_amount"
                                               class="form-control total_amount floatnumber" readonly
                                               required="" value="{{$sale_total_amount}}">
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
                                               value="{{$sale->return_date != '' && $sale->return_date != '1970-01-01' ?  date('d-M-Y',strtotime($sale->return_date)) : ""}}">
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group fg-line">
                                        <label width="10%">रिमार्क </label>
                                        <input type="text" name="remark"
                                               id="remark" class="form-control" value="{{$sale->remark}}">
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
                            {!! Form::close() !!}
                        </div>
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
            format: "DD-MMM-YYYY hh:mm:ss"

        });

        $('.return_date').datetimepicker({
            format: "DD-MMM-YYYY"

        });
    </script>

    <script>
        $(this).on('load', function () {
            var customerId = $("#customer_id").val();
            $.ajax({
                'url': "{{route('Customer.getOldDue')}}",
                type: 'get',
                data: {'customer_id': customerId},
                success: function (response) {
                    $("#old_due").val(response.due_balance);
                }
            });
        });


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


        function getTotal() {
            var total_price = 0;
            $(".total_price").each(function () {
                total_price += +$(this).val();
            });
            $("#total_amount").val(total_price)
        }

        $("#total_price").on('change', function () {
            getTotal()
        });
    </script>

    <script>
        /*Add Multiple Product*/
        $(document).ready(function () {
            var i = "{{$i}}"
            $("#addrow").on('click', function () {
                var col = "<tr>";

                col += '<td><select name="product_id[]"  id="product_id' + i + '" class="form-control productselect" required="">' +
                    '<option value=""> प्रोडक्ट चुनें </option>@foreach($product as $row => $value)<option value="{{$row}}">{{$value}}</option>@endforeach
                        </select></td>';

                col += '<td><input type="text" class="form-control qty number " name="qty[]" id="qty' + i + '"></td>';
                col += '<td><input type="text" class="form-control price floatnumber " name="price[]" id="price' + i + '" ></td>';
                col += '<td><input type="text" class="form-control total_price floatnumber " name="total_price[]" id="total_price' + i + '" ></td>';
                col += '<td><button class="btn btn-danger btn-sm" id="deleteRow" type="button"><i class="zmdi zmdi-delete"></i></button></td>';
                col += ' <input type="hidden" name="checkval[]" value="new">';
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



