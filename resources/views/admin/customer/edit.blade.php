@extends('admin.layout.app')
@section('title', 'अपडेट ग्राहक')
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('customer.index')}}"><i class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2 >अपडेट ग्राहक</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>array('customer.update',$customer->id),'method'=>'put','files'=>true ,'onsubmit'=>'submitBtn.disabled = true','return'=>true ]) !!}
                            <div class="row">
                                <div class="col-sm-2 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_id">ग्राहक आईडी</label>
                                        <input type="text" name="customer_id" id="customer_id" class="form-control " readonly
                                               required="" value="{{old('customer_id',$customer->id)}}">
                                        <div style="color: red">{{$errors->first('customer_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-4 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_name">ग्राहक का नाम <span style="color: red">* </span></label>
                                        <input type="text" name="customer_name" id="customer_name" value="{{old('customer_name',$customer->customer_name)}}"
                                               required="" class="form-control text-capitalize">
                                        <div style="color: red">{{$errors->first('customer_name')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="father_name">पिता का नाम <span style="color: red">* </span></label>
                                        <input type="text" name="father_name" id="father_name" value="{{old('father_name',$customer->father_name)}}"
                                               required="" class="form-control text-capitalize">
                                        <div style="color: red">{{$errors->first('father_name')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="mobile_no"> मोबाइल नंबर</label>
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control number"
                                               minlength="10" maxlength="10" value="{{old('mobile_no',$customer->mobile_no)}}">
                                        <div style="color: red">{{$errors->first('mobile_no')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="address">पता </label>
                                        <input type="text" name="address" id="address" value="{{old('address',$customer->address)}}"
                                                class="form-control ">
                                        <div style="color: red">{{$errors->first('address')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="adhar_no">आधार न. </label>
                                        <input type="text" id="adhar_no" name="adhar_no" class="form-control number"
                                               minlength="12" maxlength="12"  value="{{old('adhar_no',$customer->adhar_no)}}">
                                        <div style="color: red">{{$errors->first('adhar_no')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="old_due_balance">पुराना बकाया राशि  </label>
                                        <input type="text" id="old_due_balance" name="old_due_balance"  class="form-control floatnumber" value="{{old(' old_due_balance',$customer->old_due_balance)}}">
                                        <div style="color: red">{{$errors->first(' old_due_balance')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="order_no">ऑर्डर (चयनित ग्राहक के बाद)</label>
                                        {!! Form::select('order_no', [''=>'Default']+$customer_order, old('order_no',$customer->order_no), ['id'=>'order_no'  , 'class'=>'selectpicker' ,'data-dropup-auto'=>"false" ,'data-live-search'=>'true']) !!}
                                        <div style="color: red">{{$errors->first('order_no')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="route_id">रूट <span style="color: red">* </span></label>
                                        {!! Form::select('route_id', [''=>'Select']+$route, old('route_id',$customer->route_id), ['required','id'=>'route_id'  , 'class'=>'selectpicker' ,'data-dropup-auto'=>"false",'data-live-search'=>'true' ]) !!}
                                        <div style="color: red">{{$errors->first('route_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="route_id">स्थिति <span style="color: red">* </span></label>
                                        {!! Form::select('status', ['1'=>'Select','1'=>'Active','0'=>'Deactive'], old('status',$customer->status), ['required','id'=>'status'  , 'class'=>'selectpicker'  ,'data-dropup-auto'=>"false"]) !!}
                                        <div style="color: red">{{$errors->first('status')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label>ग्राहक टाइप  <span style="color: red">* </span></label>
                                        {!! Form::select('customer_type', ['0'=>'Select','0'=>'ग्राहक ','1'=>'
दुकानदार'], $customer->customer_type, ['required','id'=>'status'  , 'class'=>'selectpicker' ,'data-dropup-auto'=>"false"]) !!}
                                        <div style="color: red">{{$errors->first('customer_type')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label>ग्राहक फोटो</label>
                                        <input type="file" name="customer_photo" class="form-control "
                                               placeholder="Customer Photo " accept="image/*"
                                               onchange="readURL(this)">
                                    </div>
                                </div>
                                <div class="col-md-8"></div>
                                <div class="col-sm-4 ">
                                    <div class="form-group fg-line">
                                        <center>
                                            <img src="{{$customer->customer_photo}}" id="blah" height="100" width="150"/>
                                        </center>
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
    <script>
        $('.number').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
            }
        });
        $('.floatnumber').on('input', function () {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(100);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush



