@extends('admin.layout.app')
@section('title', 'अपडेट एडवांस पेमेंट ')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
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
                            <a class="btn btn-primary btn-block" href="{{route('advancepayment.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>अपडेट एडवांस पेमेंट</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>array('advancepayment.update',$data->id),'method'=>'put','onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="salesman_id">सेल्समैन <span
                                                style="color: red">*</span></label>
                                        {!! Form::select('salesman_id', [''=>'सेल्समैन चुने' ]+$salesman,$data->salesman_id, ['required','id'=>'salesman_id' ,'class'=>'selectpicker' ,'data-dropup-auto'=>"false" ,'data-live-search'=>'true' ]) !!}
                                        <div style="color: red">{{$errors->first('salesman_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="amount">एडवांस राशि <span style="color: red">*</span></label>
                                        <input type="text" name="amount" id="amount" value="{{old('amount',$data->amount)}}"
                                               required="" class="form-control number" maxlength="">
                                        <div style="color: red">{{$errors->first('amount')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="adv_date"> तिथि <span
                                                style="color: red">*</span></label>
                                        <input type="text" id="adv_date"
                                               name="adv_date" class="form-control date-picker created_date"
                                               required="" value="{{old('adv_date',date('d-M-Y H:i:s',strtotime($data->adv_date)))}}">
                                        <div style="color: red">{{$errors->first('adv_date')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <label for="amount">रिमार्क </label>
                                        <textarea type="text" name="remark" id="remark"
                                                  class="form-control " maxlength="">{{old('amount',$data->remark)}}</textarea>
                                        <div style="color: red">{{$errors->first('remark')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group fg-line">
                                        <button type="submit" id="submitBtn" class="btn btn-primary " name="submit" value="1">सेव</button>  &nbsp&nbsp&nbsp
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
            format: "DD-MMM-YYYY HH:mm:ss",
        });

    </script>


@endpush
