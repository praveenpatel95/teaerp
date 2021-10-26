@extends('salesman.layout.app')
@section('title', 'Add Customer')
@push('headerscript')
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('salesmancustomer.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> Back</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>Add Customer</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>'salesmancustomer.store','method'=>'post', 'files'=>true,'onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="customer_name">Name <span style="color: red">* </span></label>
                                        <input type="text" name="customer_name" id="customer_name"
                                               value="{{old('customer_name')}}"
                                               required="" class="form-control ">
                                        <div style="color: red">{{$errors->first('customer_name')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="father_name">Father Name <span style="color: red">* </span></label>
                                        <input type="text" name="father_name" id="father_name"
                                               value="{{old('father_name')}}"
                                               required="" class="form-control">
                                        <div style="color: red">{{$errors->first('father_name')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="mobile_no"> Mobile No. </label>
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control number"
                                               minlength="10" maxlength="10" value="{{old('mobile_no')}}">
                                    </div>
                                </div>

                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="address"> Address </label>
                                        <input type="text" name="address" id="address" value="{{old('address')}}"
                                               class="form-control ">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="adhar_no">Adhar No. </label>
                                        <input type="text" id="adhar_no" name="adhar_no" c
                                               class="form-control number"
                                               minlength="12" maxlength="12"
                                               value="{{old('adhar_no')}}">
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="opening_balance">Opening Due Balance <span
                                                style="color: red">* </span></label>
                                        <input type="text" step="any" id="opening_balance" name="opening_balance" c
                                               class="form-control floatnumber"
                                               required="" value="{{old(' opening_balance')}}">
                                        <div style="color: red">{{$errors->first(' opening_balance')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="order_no"> Order (After Selected Customer) </label>
                                        {!! Form::select('order_no', [''=>'Default']+$customer_order, old('order_no'), ['id'=>'order_no'  , 'class'=>'selectpicker' ,'data-dropup-auto'=>"false",'data-live-search'=>"true" ]) !!}
                                        <div style="color: red">{{$errors->first('order_no')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="route_id">Route <span style="color: red">* </span></label>
                                        {!! Form::select('route_id', [''=>'Select']+$route, old('route_id'), ['required','id'=>'route_id'  , 'class'=>'selectpicker' ,'data-dropup-auto'=>"false"  ,'data-live-search'=>"true"  ]) !!}
                                        <div style="color: red">{{$errors->first('route_id')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="route_id">Status <span style="color: red">* </span></label>
                                        {!! Form::select('status', ['1'=>'Select','1'=>'Active','0'=>'Deactive'], old('status'), ['required','id'=>'status'  , 'class'=>'selectpicker' ,'data-dropup-auto'=>"false" ]) !!}
                                        <div style="color: red">{{$errors->first('status')}}</div>
                                    </div>
                                </div>

                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label>Customer Photo</label>
                                        <input type="file" name="customer_photo" class="form-control "
                                               placeholder="Customer Photo " accept="image/*"
                                               onchange="readURL(this)">
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <center>
                                            <img src="" id="blah"/>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
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
                        <a class="btn btn-float bgm-red m-btn" href="{{route('salesmancustomer.index')}}"><i
                                class="zmdi zmdi-long-arrow-return"></i></a>
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



