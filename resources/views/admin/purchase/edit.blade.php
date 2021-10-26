@extends('admin.layout.app')
@section('title', 'अपडेट  पर्चेज ')
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
                            <a class="btn btn-primary btn-block" href="{{route('purchase.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>

                        <div class="block-header m-t-20 ">
                            <h2>अपडेट  पर्चेज </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>array('purchase.update',$purchase->id),'method'=>'put']) !!}
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="product_id">प्रोडक्ट <span
                                                style="color: red">* </span></label>
                                        {!!form::select('product_id',[''=>'Select']+$product,old('product_id',$purchase->product_id),['required','id'=>'product_id','class'=>'selectpicker' ,'data-live-search'=>'true']) !!}
                                        <div style="color: red">{{$errors->first('product_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="qty">मात्रा <span style="color: red">* </span></label>
                                        <input type="text" name="qty" id="qty" value="{{old('qty',$purchase->qty)}}"
                                               required="" class="form-control number">
                                        <div style="color: red">{{$errors->first('qty')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="qty">पर्चेज कीमत <span style="color: red">* </span></label>
                                        <input type="text" name="purchase_price" id="purchase_price" value="{{old('purchase_price',$purchase->purchase_price)}}"
                                               required="" class="form-control floatnumber">
                                        <div style="color: red">{{$errors->first('purchase_price')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="purchase_date">पर्चेज तिथी <span
                                                style="color: red">* </span></label>
                                        <input type="text" id="purchase_date"
                                               name="purchase_date" class="form-control date-picker created_date"
                                               required="" value="{{old('purchase_date',$purchase->purchase_date)}}">
                                        <div style="color: red">{{$errors->first('purchase_date')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <label for="remark"> रिमार्क</label>
                                        <input type="text" name="remark" id="remark" class="form-control "
                                               value="{{old('remark',$purchase->remark)}}">
                                        <div style="color: red">{{$errors->first('remark')}}</div>
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
            format: "DD-MMM-YYYY"
        });
    </script>
@endpush

