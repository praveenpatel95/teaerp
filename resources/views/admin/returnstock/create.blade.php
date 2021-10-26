@extends('admin.layout.app')
@section('title', 'रिटर्न स्टॉक ')
@push('headerscript')
    <link href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
    <style>
        .tdwidth {
            width: 96% !important;
            margin: 2px !important;
        }

        .tdselect {
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
                            <a class="btn btn-primary btn-block" href="{{route('returnstock.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2 >रिटर्न स्टॉक</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>'returnstock.store','method'=>'post','onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="salesman_id">सेल्समैन<span style="color: red">* </span></label>
                                        {!! Form::select('salesman_id', [''=>'सेल्समैन चुने' ]+$salesman,old('salesman_id'), ['required','id'=>'salesman_id' ,'class'=>'selectpicker','data-dropup-auto'=>"false" ,'data-live-search'=>'true']) !!}
                                        <div style="color: red">{{$errors->first('salesman_id')}}</div>
                                    </div>
                                </div>
                                {{--<div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="product_id">प्रोडक्ट<span style="color: red">* </span></label>
                                        {!! Form::select('product_id', [''=>'प्रोडक्ट चुने' ]+$product,old('product_id'), ['required','id'=>'product_id' ,'class'=>'selectpicker' ,'data-dropup-auto'=>"false" ,'data-live-search'=>'true']) !!}
                                        <div style="color: red">{{$errors->first('product_id')}}</div>
                                    </div>
                                </div>--}}
                                {{--<div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="qty">मात्रा <span style="color: red">* </span></label>
                                        <input type="text" name="qty" id="qty" value="{{old('qty')}}"
                                               required="" class="form-control number">
                                        <div style="color: red">{{$errors->first('qty')}}</div>
                                    </div>
                                </div>--}}
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="return_date">रिटर्न तिथि <span
                                                style="color: red">* </span></label>
                                        <input type="text" id="return_date"
                                               name="return_date" class="form-control date-picker created_date"
                                               required="" value="{{old('return_date',date('d-M-Y'))}}">
                                        <div style="color: red">{{$errors->first('return_date')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <table id="product_table">
                                        <thead>
                                        <tr class="text-center">
                                            <th width="53%">प्रोडक्ट<span
                                                    class="text-danger">*</span>
                                            </th>
                                            <th width="47%">मात्रा <span class="text-danger">*</span></th>

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
                                            <td>
                                                {!! Form::select("product_id[]", [''=>'प्रोडक्ट चुने' ]+$product,old('product_id'), ['required','id'=>'product_id' ,'class'=>'form-control productselect tdwidth' ,'data-dropup-auto'=>"false" ,'data-live-search'=>'true' ]) !!}
                                            </td>

                                            <td>
                                                <input type="text" name="qty[]" id="return_stock" value="{{old('qty')}}"
                                                       required="" class="form-control number tdwidth" maxlength="">
                                            </td>

                                            <td>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-sm-12 <!-- m-t-20 -->">
                                    <div class="form-group fg-line">
                                        <button type="submit" id="submitBtn" class="btn btn-primary " name="submit" value="1">सेव</button>  &nbsp&nbsp&nbsp
                                        <button type="submit" id="submitBtn" class="btn btn-success " name="submit"  value="2">सेव & रिटर्न</button>
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
        $('.created_date').datetimepicker({
            format: "DD-MMM-YYYY"
        });
    </script>
    <script>
        /*Add Multiple Product*/
        $(document).ready(function () {
            var i = 2;
            $("#addrow").on('click', function () {
                var col = "<tr>";
                col += '<td><select  name="product_id[]" class="form-control productselect tdwidth"  id="product_id' + i + '"  required="">' +
                    '<option value=""> प्रोडक्ट चुनें </option>@foreach($product as $row => $value)<option value="{{$row}}">{{$value}}</option>@endforeach
                        </select></td>';
                col += '<td><input type="text" class="form-control qty number tdwidth " name="qty[]" id="qty' + i + '"></td>';
                col += '<td><button class="btn btn-danger" id="deleteRow" type="button"><i class="zmdi zmdi-delete"></i></button></td>';
                $("table#product_table").append(col);
                i++;

                $('.number').keyup(function (e) {
                    if (/\D/g.test(this.value)) {
                        this.value = this.value.replace(/\D/g, '');
                    }
                });

                $('.floatnumber').on('input', function () {
                    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                });

                $("table#product_table").on("click", "#deleteRow", function (event) {
                    $(this).closest("tr").remove();
                });


            });
        });
    </script>


@endpush



