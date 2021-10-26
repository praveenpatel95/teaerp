@extends('admin.layout.app')
@section('title', 'अपडेट खर्च')
@push('headerscript')
    <link href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('expense.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>अपडेट खर्च</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding">
                            {!! Form::open(['route'=>array('expense.update',$expense->id),'method'=>'put','onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="expense_name">खर्च का नाम <span style="color: red">* </span></label>
                                        <input type="text" name="expense_name" id="expense_name"
                                               value="{{old('expense_name',$expense->expense_name)}}"
                                               required="" class="form-control text-capitalize">
                                        <div style="color: red">{{$errors->first('expense_name')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="amount">राशि <span style="color: red">* </span></label>
                                        <input type="text" name="amount" id="amount"
                                               value="{{old('amount',$expense->amount)}}"
                                               required="" class="form-control floatnumber">
                                        <div style="color: red">{{$errors->first('amount')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="salesman_id">सेल्समेन </label>
                                        {!! Form::select('salesman_id', [''=>'सेल्समेन चुने']+$salesman, $expense->salesman_id, ['id'=>'salesman_id'  , 'class'=>'selectpicker','data-dropup-auto'=>"false" ,'data-live-search'=>'true' ]) !!}
                                        <div style="color: red">{{$errors->first('salesman_id')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="form-group fg-line">
                                        <label for="expense_date">तारीख़ <span style="color: red">* </span></label>
                                        <input type="text" name="expense_date" id="expense_date"
                                               value="{{old('expense_date',date('d-M-Y',strtotime($expense->expense_date)))}}"
                                               required="" class="form-control  created_date">
                                        <div style="color: red">{{$errors->first('expense_date')}}</div>
                                    </div>
                                </div>
                                <div class="col-sm-12 ">
                                    <div class="form-group fg-line">
                                        <label for="detail">डिटेल </label>
                                        <input type="text" name="detail" id="detail" value="{{old('detail',$expense->detail)}}"
                                                 class="form-control ">
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
    <script src="{{asset('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        $('.created_date').datetimepicker({
            format: "DD-MMM-YYYY"
        });

        $('.floatnumber').on('input', function () {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        });
    </script>
@endpush



