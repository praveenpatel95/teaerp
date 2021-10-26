@extends('admin.layout.app')
@section('title', 'अपडेट असाइन स्टॉक')
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
                            <a class="btn btn-primary btn-block" href="{{route('assignstock.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>अपडेट असाइन स्टॉक</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>array('assignstock.update',$assign_stock->id),'method'=>'put']) !!}
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="salesman_id">सेल्समैन <span style="color: red">*</span></label>
                                        {!! Form::select('salesman_id', [''=>'सेल्समैन चुने' ]+$salesman,old('salesman_id',$assign_stock->salesman_id), ['required','id'=>'salesman_id' ,'class'=>'selectpicker','data-dropup-auto'=>"false" ,'data-live-search'=>'true' ]) !!}
                                        <div style="color: red">{{$errors->first('salesman_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="product_id">प्रोडक्ट<span style="color: red">*</span></label>
                                        {!! Form::select('product_id', [''=>'प्रोडक्ट चुने' ]+$product,old('product_id',$assign_stock->product_id), ['required','id'=>'product_id' ,'class'=>'selectpicker' ,'data-dropup-auto'=>"false",'data-live-search'=>'true' ]) !!}
                                        <div style="color: red">{{$errors->first('product_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="qty">मात्रा <span style="color: red">*</span></label>
                                        <input type="text" name="qty" id="assign_stock"
                                               value="{{old('qty',$assign_stock->qty)}}"
                                               required="" class="form-control number">
                                        <div style="color: red" id="max_stock">{{$errors->first('qty')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="assign_date">असाइन तिथि <span
                                                style="color: red">*</span></label>
                                        <input type="text" id="assign_date"
                                               name="assign_date" class="form-control date-picker created_date"
                                               required=""
                                               value="{{old('assign_date',date('d-M-Y',strtotime($assign_stock->assign_date)))}}">
                                        <div style="color: red">{{$errors->first('assign_date')}}</div>
                                    </div>
                                </div>
                                <input type="hidden" id="in_stock"
                                       name="in_stock" class="form-control "
                                       value="">
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
        $('.created_date').datetimepicker({
            format: "DD-MMM-YYYY"
        });
        $('#product_id').on('change', function () {
            var product_id = $(this).val();
            $.ajax({
                url: "{{route('maxAssignStock.getData')}}",
                type: 'get',
                data: {'product_id': product_id},
                success: function (result) {
                    $("#in_stock").val(result.in_stock);
                    $("#assign_stock").val('');
                }
            });
        });

        $('#assign_stock').on('click', function () {
            var product_id = $('#product_id').val();
            $.ajax({
                url: "{{route('maxAssignStock.getData')}}",
                type: 'get',
                data: {'product_id': product_id},
                success: function (result) {
                    $("#in_stock").val(result.in_stock);
                }
            });
        });

        $('#assign_stock').on('input', function () {
            var value = $(this).val();
            var in_stock = $('#in_stock').val();
            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, in_stock)));
                $('#max_stock').html('उपलब्ध स्टॉक केवल ' + in_stock + '&nbsp है');
            }
        });
    </script>
@endpush



