@extends('admin.layout.app')
@section('title', 'प्रोडक्ट जोड़े')
@push('headerscript')
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('product.index')}}"><i class="zmdi zmdi-long-arrow-return"> </i>  पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20">
                            <h2 >प्रोडक्ट जोड़े</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>'product.store','method'=>'post' ,'onsubmit'=>'submitBtn.disabled = true','return'=>true ]) !!}
                            <div class="row">
                                <div class="col-sm-4 ">
                                    <div class="form-group fg-line">
                                        <label for="product_name">प्रोडक्ट का नाम <span style="color: red">* </span></label>
                                        <input type="text" name="product_name" id="product_name" value="{{old('product_name')}}"
                                               required="" class="form-control">
                                        <div style="color: red">{{$errors->first('product_name')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-2 ">
                                    <div class="form-group fg-line">
                                        <label for="in_stock">Piece (पीस ) <span style="color: red">* </span></label>
                                        <input type="text" name="in_stock" id="in_stock" class="form-control number"
                                               required="" value="{{old('in_stock')}}">
                                        <div style="color: red">{{$errors->first('in_stock')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="weight">Weight (वजन) <span style="color: red">* </span> </label>
                                        <input type="text" name="weight" id="weight" class="form-control floatnumber"
                                               required="" value="{{old('weight')}}">
                                        <div style="color: red">{{$errors->first('weight')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-3 ">
                                    <div class="form-group fg-line">
                                        <label for="unite">Unite (इकाई)  </label>
                                        <input type="text" name="unite" id="unite" class="form-control number" readonly
                                               required="" value="Kg">
                                         <div style="color: red">{{$errors->first('unite')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="purchase_price"> पर्चेज कीमत<span style="color: red">* </span></label>
                                        <input type="text"  name="purchase_price" id="purchase_price"
                                               required="" value="{{old('purchase_price')}}" class="form-control floatnumber">
                                        <div style="color: red">{{$errors->first('purchase_price')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="sale_price">सेल कीमत<span style="color: red">* </span></label>
                                        <input type="text"  name="sale_price" id="sale_price" class="form-control floatnumber"
                                               required="" value="{{old('sale_price')}}">
                                        <div style="color: red">{{$errors->first('sale_price')}}</div>
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
    </script>
@endpush



