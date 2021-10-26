@extends('admin.layout.app')
@section('title', 'अपडेट सेल्समेन')
@push('headerscript')
    <link href="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('theme/vendors/bower_components/bootstrap-select/dist/css/bootstrap-select.css')}}" rel="stylesheet">
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('salesman.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>अपडेट सेल्समेन</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route' => array('salesman.update',$salesman->id),'method'=>'put','onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="name">नाम <span style="color: red">* </span></label>
                                        <input type="text" name="name" id="name" value="{{old('name',$salesman->name)}}"
                                               required="" class="form-control">
                                        <div style="color: red">{{$errors->first('name')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="name">पिता का नाम <span style="color: red">* </span></label>
                                        <input type="text" name="father_name" id="name" value="{{old('name',$salesman->father_name)}}"
                                               required="" class="form-control">
                                        <div style="color: red">{{$errors->first('father_name')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="mobile_no">मोबाइल नंबर</label>
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control number"
                                               minlength="10" maxlength="10"
                                               value="{{old('mobile_no',$salesman->mobile_no)}}">
                                        <div style="color: red">{{$errors->first('mobile_no')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="address">पता </label>
                                        <input type="text" name="address" id="address" value="{{old('address',$salesman->address)}}"
                                               class="form-control">
                                        <div style="color: red">{{$errors->first('address')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="adhar_no">आधार न. </label>
                                        <input type="text" id="adhar_no" name="adhar_no"
                                                maxlength="12" minlength="12" value="{{old('adhar_no',$salesman->adhar_no)}}"
                                               class="form-control number">
                                        <div style="color: red">{{$errors->first('adhar_no')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <div class="form-group fg-line">
                                        <label for="salary_type">वेतन प्रकार <span style="color: red">* </span></label>
                                        {!! Form::select('salary_type', ['Hourly'=>'चुने' ,'Hourly'=>'
प्रति घंटा'],$salesman->salary_type, ['required','id'=>'salary_type' ,'class'=>'selectpicker' ,'data-dropup-auto'=>"false" ]) !!}

                                        <div style="color: red">{{$errors->first('salary_type')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <div class="form-group fg-line">
                                        <label for="salary_amount">वेतन राशि <span
                                                style="color: red">* </span></label>
                                        <input type="text" id="salary_amount"
                                               name="salary_amount" class="form-control floatnumber"
                                               required="" value="{{old('salary_amount',$salesman->salary_amount)}}">
                                        <div style="color: red">{{$errors->first('salary_amount')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <div class="form-group fg-line">
                                        <label for="commision">कमीशन (%)</label>
                                        <input type="text"  id="commision"
                                               name="commission" class="form-control floatnumber"
                                               placeholder="%" min="1" max="100"
                                             value="{{old('commission',$salesman->commission)}}">
                                        <div style="color: red">{{$errors->first('commission')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="joining_date">जोइनिंग तारीख़ <span
                                                style="color: red">* </span></label>
                                        <input type="text" id="joining_date"
                                               name="joining_date" class="form-control date-picker created_date"
                                               required="" value="{{old('salary_amount',date('d-M-Y',strtotime($salesman->joining_date)))}}">
                                        <div style="color: red">{{$errors->first('joining_date')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="status">स्थिति <span style="color: red">* </span></label>
                                        {!! Form::select('status', ['1'=>'सक्रिय','0'=>'निष्क्रिय'], $user->status, ['required','id'=>'status' ,'class'=>'selectpicker','data-dropup-auto'=>"false" ]) !!}

                                        <div style="color: red">{{$errors->first('status')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="line_id">लाइन <span style="color: red">* </span></label>
                                        {!! Form::select('line_id', [''=>'चुने']+$line, $salesman->line_id, ['required','id'=>'line_id'  ,'class'=>'selectpicker'  ,'data-live-search'=> 'true'  ,'data-dropup-auto'=>"false"]) !!}

                                        <div style="color: red">{{$errors->first('line_id')}}</div>
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

        $('#commission').on('input', function () {

            var value = $(this).val();
            var maximum = 100;

            if ((value !== '') && (value.indexOf('.') === -1)) {

                $(this).val(Math.max(Math.min(value, maximum)));
            }
        });
    </script>


@endpush



