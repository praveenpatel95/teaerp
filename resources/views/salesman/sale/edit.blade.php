@extends('salesman.layout.app')
@section('title', 'Update Sale')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <style>
        .productselect {
            padding-bottom: 5px !important;
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
                            <h2>Update Sale</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>array('salesmansale.update',$sale->id),'method'=>'put','onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block-header m-t-20 ">
                                        <h2>Basic Detail</h2>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_id">Customer <span style="color: red">* </span></label>
                                        {!! Form::select('customer_id', [''=>'Select']+$customer, $sale->customer_id, ['required','id'=>'customer_id'  , 'class'=>'selectpicker' ,'data-live-search'=> 'true' ,'data-dropup-auto'=>"false" ]) !!}
                                        <div style="color: red">{{$errors->first('customer_id')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label for="rec_no"> Sale No. <span style="color: red">* </span></label>
                                        <input type="text" name="sale_no" id="sale_no" class="form-control " readonly
                                               value="{{old('rec_no',$sale->sale_no)}}">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group fg-line">
                                        <label for="sale_date">Sale Date <span
                                                style="color: red">* </span></label>
                                        <input type="text" id="sale_date"
                                               name="sale_date" class="form-control date-picker created_date"
                                               required=""
                                               value="{{old('sale_date',date('d-M-Y',strtotime($sale->sale_date)))}}">
                                        <div style="color: red">{{$errors->first('sale_date')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="block-header m-t-20 ">
                                        <h2>Sale Product</h2>
                                    </div>
                                </div>
                                {{--Sale Product--}}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="table-responsive">
                                                <table id="product_table" class="table table-striped">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th>Product</th>
                                                        <th width="10%">Quantity</th>
                                                        <th width="10%">Unit</th>
                                                        <th>Price</th>
                                                        <th>Total Price</th>
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
                                                                {!! Form::select('product_id[]', [''=>'Select Product' ] + $product,$row->product_id, ['required','id'=>'product_id'.$i ,'class'=>'form-control productselect' ,'data-dropup-auto'=>"false"  ]) !!}
                                                            </td>

                                                            <td>
                                                                <input type="text" name="qty[]"
                                                                       id="qty{{$i}}" required="" value="{{$row->qty}}"
                                                                       class="form-control qty floatnumber">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="unite[]"
                                                                       id="unite{{$i}}" value="{{$row->unite}}" readonly
                                                                       class="form-control ">
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
                                                                <a href="{{route('salesmansaleproduct.delete',$row->id)}}"
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
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4 ">
                                    <br>
                                    <div class="form-group fg-line">
                                        <label for="total_amount">Total Amount <span
                                                style="color: red">* </span></label>
                                        <input type="text" name="total_amount" id="total_amount"
                                               class="form-control total_amount floatnumber" readonly
                                               required="" value="{{$sale_total_amount}}">
                                        <div style="color: red">{{$errors->first('total_amount')}}</div>
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
                    '<option value=""> Select Product </option>@foreach($product as $row => $value)<option value="{{$row}}">{{$value}}</option>@endforeach
                        </select></td>';

                col += '<td><input type="text" class="form-control qty number " name="qty[]" id="qty' + i + '"></td>';
                col += '<td><input type="text" class="form-control  " readonly name="qty[]" id="unite' + i + '"></td>';
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



