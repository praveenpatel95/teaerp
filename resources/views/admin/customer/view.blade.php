@extends('admin.layout.app')
@section('title', 'ग्राहक व्यू')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="block-header m-t-20 m-l-5">
                    <div class="col-sm-4">
                        <div class="block-header m-t-5 ">
                            <h2>ग्राहक व्यू</h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="alert alert-info" role="alert">कुल सेल राशि : @if($data){{$sale_total_amount}}@endif
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="alert alert-success" role="alert">जमा राशि : @if($data){{$pay_amount}}@endif
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="alert alert-danger" role="alert">बकाया राशि : {{$customer->due_balance}}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="btn-group">
                            <a class="btn btn-info btn-block" href="{{route('customersale',$customer->id)}}"><i
                                    class="zmdi zmdi-shopping-cart-add"> </i> सेल जोड़ें</a>
                        </div>
                        <div class="btn-group pull-right ">
                            <a class="btn btn-primary btn-block" href="{{route('customer.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Profile view -->
            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card profile-view">
                            <div class="pv-header">
                                <img src="{{$customer->customer_photo}}" class="pv-main">
                            </div>
                            <div class="pv-body">
                                <div class="card-header ch-alt ">
                                    <h2>{{strtoupper($customer->customer_name)}} </h2>
                                </div>
                                <div class="card-body card-padding">
                                    <div class="pmo-contact">
                                        <ul>
                                            <li class="ng-binding text-justify"> पिता का नाम
                                                : {{$customer->father_name}}</li>
                                            <li class="ng-binding  text-justify"> मोबाइल
                                                नंबर: {{$customer->mobile_no}}</li>
                                            <li class="ng-binding  text-justify"> आधार न.: {{$customer->adhar_no}}</li>
                                            <li class="ng-binding  text-justify"> पता: {{$customer->address}}</li>
                                            <li class="ng-binding  text-justify"> ग्राहक आईडी: {{$customer->id}} </li>
                                            <li class="ng-binding  text-justify"> ऑर्डर न.: {{$customer->order_no}}</li>
                                            <li class="ng-binding  text-justify"> रूट: {{$customer->route_name}}</li>
                                            <li class="ng-binding  text-justify "> पुराना बकाया राशि: <b
                                                    class="text-danger">{{$customer->old_due_balance}}</b></li>
                                        </ul>
                                        <br>

                                        <button type="button"
                                                class="btn btn-primary btn-sm"
                                                onclick="addoldduemodel({{$customer->id}})"
                                                data-toggle="modal" title="Add Payment"
                                                data-target="#olddue"><i
                                                class="zmdi zmdi-paypal"> पुराना बकाया जमा करे </i>
                                        </button>

                                        <a class="btn btn-info btn-sm pull-right"
                                           href="{{route('customer.edit',$customer->id)}}"><i
                                                class="zmdi zmdi-edit"> ग्राहक एडिट करे</i> </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <ul class="tab-nav tn-justified tn-icon" role="tablist">
                                <li role="presentation" class="active">
                                    <a class="col-xs-4" href="#tab-3" aria-controls="tab-3 role=" tab"
                                    data-toggle="tab">
                                    सेल इन्वॉइस
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a class="col-sx-4" href="#tab-1" aria-controls="tab-1" role="tab"
                                       data-toggle="tab">
                                        प्रोडक्ट पर्चेज हिस्ट्री
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a class="col-sx-4" href="#tab-4" aria-controls="tab-4" role="tab"
                                       data-toggle="tab">
                                        प्रोडक्ट रिटर्न हिस्ट्री
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a class="col-xs-4" href="#tab-2" aria-controls="tab-2" role="tab"
                                       data-toggle="tab">
                                        पेमेंट हिस्ट्री
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a class="col-xs-4" href="#tab-5" aria-controls="tab-5" role="tab"
                                       data-toggle="tab">
                                        सेल ब्याज
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a class="col-xs-4" href="#tab-6" aria-controls="tab-5" role="tab"
                                       data-toggle="tab">
                                        गिफ्ट
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-20">
                                <div role="tabpanel" class="tab-pane animated fadeIn in active " id="tab-3">
                                    <div class="row">
                                        @if($data)
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-0">
                                                    <tbody>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>सेल न.</th>
                                                        <th>सेल की तिथी</th>
                                                        <th>कुल राशि</th>
                                                        <th>जमा राशि</th>
                                                        <th>बकाया राशि</th>
                                                        <th>ऐक्शन</th>
                                                    </tr>
                                                    @foreach($saledata as $row)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$row->sale_no}}</td>
                                                            <td>{{date('j M, Y h:ia',strtotime($row->sale_date))}}</td>
                                                            <td>{{$row->total_amount}}</td>
                                                            <td>{{$row->PayAmount}}</td>
                                                            <td>{{$row->total_amount-$row->PayAmount}}</td>

                                                            <td><a href="{{route('invoice',$row->id)}}"
                                                                   class="btn btn-info btn-sm"
                                                                   type="button"><i class="zmdi zmdi-print"></i></a>&nbsp;
                                                                <button type="button"
                                                                        class="btn btn-primary btn-sm"
                                                                        onclick="addpaymentmodel({{$row->id}})"
                                                                        data-toggle="modal" title="Add Payment"
                                                                        data-target="#addpayment"><i
                                                                        class="zmdi zmdi-paypal"> </i>
                                                                </button>&nbsp;
                                                                <a href="{{route('sale.edit',$row->id)}}"
                                                                   class="btn btn-success btn-sm"
                                                                   type="button"><i class="zmdi zmdi-edit"></i></a>&nbsp;
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane animated fadeIn in " id="tab-1">
                                    <div class="row">
                                        @if($data)
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-0">
                                                    <tbody>
                                                    <tr>
                                                        <th>सेल न.</th>
                                                        <th>प्रोडक्ट</th>
                                                        <th>मात्रा</th>
                                                        <th>कीमत</th>
                                                        <th>कुल कीमत</th>
                                                        <th>पर्चेज दिनांक</th>
                                                        <th>ऐक्शन</th>
                                                    </tr>
                                                    @foreach($sale_product as $row)
                                                        <tr>
                                                            <td>{{$row->sale_no}}</td>
                                                            <td>{{$row->product_name}}</td>
                                                            <td>{{$row->qty}} &nbsp{{$customer->unite}}</td>
                                                            <td>{{$row->price}}</td>
                                                            <td>{{$row->total_price}}</td>
                                                            <td>{{date('j M, Y h:ia',strtotime($row->created_at))}}</td>
                                                            <td>
                                                                @if($row->status == 0)
                                                                    <button type="button"
                                                                            class="btn btn-primary btn-sm"
                                                                            onclick="model({{$row->id}})"
                                                                            data-toggle="modal"
                                                                            data-target="#editproduct"><i
                                                                            class="zmdi zmdi-edit"></i>
                                                                    </button>
                                                                    &nbsp;
                                                                    <button onclick="returnIt({{$row->id}})"
                                                                            class="btn btn-default btn-sm"><i
                                                                            class="zmdi zmdi-assignment-return"> </i>
                                                                        रिटर्न करे
                                                                    </button>
                                                                @else
                                                                    <button disabled class="btn btn-warning btn-sm">
                                                                        <i
                                                                            class="zmdi zmdi-assignment-return"> </i>
                                                                        रिटर्नड
                                                                    </button>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane animated fadeIn in " id="tab-4">
                                    <div class="row">
                                        @if($data)
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-0">
                                                    <tbody>
                                                    <tr>
                                                        <th>सेल न.</th>
                                                        <th>प्रोडक्ट</th>
                                                        <th>मात्रा</th>
                                                        <th>कीमत</th>
                                                        <th>कुल कीमत</th>
                                                        <th>रिटर्न दिनांक</th>
                                                        <th>ऐक्शन</th>
                                                    </tr>
                                                    @foreach($sale_product_return as $row)
                                                        <tr>
                                                            <td>{{$row->sale_no}}</td>

                                                            <td>{{$row->product_name}}</td>
                                                            <td>{{$row->qty}} &nbsp{{$customer->unite}}</td>
                                                            <td>{{$row->price}}</td>
                                                            <td>{{$row->total_price}}</td>
                                                            <td>{{date('j M, Y h:ia',strtotime($row->updated_at))}}</td>
                                                            <td>
                                                                <button disabled class="btn btn-warning btn-sm">
                                                                    <i
                                                                        class="zmdi zmdi-assignment-return"> </i>
                                                                    रिटर्नड
                                                                </button> &nbsp;
                                                                <a href="{{route('ReturnReceipt',$row->id)}}"
                                                                   class="btn btn-success btn-sm"
                                                                   type="button"><i class="zmdi zmdi-print"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane animated fadeIn" id="tab-2">
                                    <div class="row">
                                        @if($data)
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-0">
                                                    <tbody>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>सेल न.</th>
                                                        <th>रसीद न.</th>
                                                        <th>पेमेंट का तरीका</th>
                                                        <th>पेड राशि</th>
                                                        <th>पेमेंट की तिथि</th>
                                                        <th>रिमार्क</th>
                                                        <th>ऐक्शन</th>
                                                    </tr>
                                                    @foreach($sale_pay as $row)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$row->sale_no}}</td>
                                                            <td>{{$row->rec_no}}</td>
                                                            @if($row->paymode == 1)
                                                                <td>Cash</td>
                                                            @elseif($row->paymode == 2)
                                                                <td>Account</td>
                                                            @elseif($row->paymode == 3)
                                                                <td>Paytm</td>
                                                            @elseif($row->paymode == 4)
                                                                <td>Phone Pay</td>
                                                            @elseif($row->paymode == 5)
                                                                <td>Google Pay</td>
                                                            @else
                                                                <td>Adhar Card</td>
                                                            @endif
                                                            <td>{{$row->pay_amount}}</td>
                                                            <td>{{date('j M, Y h:ia',strtotime($row->pay_date))}}</td>
                                                            <td>{{$row->remark}}</td>
                                                            <td>
                                                                <a href="{{route('paymentReceipt',$row->id)}}"
                                                                   class="btn btn-success btn-sm"
                                                                   type="button"><i class="zmdi zmdi-print"></i>
                                                                </a>
                                                                &nbsp
                                                                <button type="button"
                                                                        class="btn btn-primary btn-sm"
                                                                        onclick="paymentmodel({{$row->id}})"
                                                                        data-toggle="modal"
                                                                        data-target="#editpayment"><i
                                                                        class="zmdi zmdi-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div role="tabpane5" class="tab-pane animated fadeIn" id="tab-5">
                                    <div class="row">
                                        @if($data)
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-0">
                                                    <tbody>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>सेल न.</th>
                                                        <th>राशि</th>
                                                        <th>प्रतिशत</th>
                                                        <th>ब्याज राशि</th>
                                                        <th>दिनांक</th>
                                                        <th>स्थिति</th>
                                                        <th>ऐक्शन</th>
                                                    </tr>
                                                    @foreach($interest_data as $row)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$row->sale_no}}</td>
                                                            <td>{{$row->amount}}</td>
                                                            <td>{{$row->percentage}}</td>
                                                            <td>{{$row->interest_amount}}</td>
                                                            <td>{{date('d-M-Y',strtotime($row->interest_date))}}</td>
                                                            @if($row->status == 0)
                                                                <td style="font-size: 15px ;color: red">
                                                                    बाकि
                                                                </td>

                                                            @else
                                                                <td style="font-size: 15px ;color: green">
                                                                    जमा
                                                                </td>
                                                            @endif

                                                            <td>
                                                                @if($row->status == 0)

                                                                    <button onclick="InterestSubmit({{$row->id}})"
                                                                            class="btn btn-primary btn-sm"><i
                                                                            class="zmdi zmdi-assignment-return"> </i>
                                                                        जमा करे
                                                                    </button>
                                                                @else
                                                                    <a href="{{route('SaleInterestReceipt',$row->id)}}"
                                                                       class="btn btn-success btn-sm"
                                                                       type="button"><i class="zmdi zmdi-print"></i>
                                                                    </a>

                                                                @endif


                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div role="tabpane6" class="tab-pane animated fadeIn" id="tab-6">
                                    <div class="row">
                                        @if($data)
                                            <div class="table-responsive">
                                                <table class="table table-bordered m-0">
                                                    <tbody>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>गिफ्ट प्रोडक्ट</th>
                                                        <th>क़ीमत</th>
                                                        <th>गिफ्ट दिनांक</th>
                                                        <th>स्थिति</th>
                                                    </tr>
                                                    @foreach($gift_data as $row)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$row->gift_product}}</td>
                                                            <td>{{$row->price}}</td>
                                                            <td>{{date('d-M-Y',strtotime($row->gift_date))}}</td>
                                                            @if($row->status==0)
                                                                <td style="color: red">Pending</td>
                                                            @else
                                                                <td style="color: green">Dispatched</td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--model for Update product--}}
    <div class="modal fade" id="editproduct" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">अपडेट प्रोडक्ट</h4>
                </div>
                {!! Form::open(['route' =>'saleproductupdate','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="sale_product_id" value="" class="dt">
                        <input type="hidden" value="{{$customer->customer_id}}" name="sale_id">
                        <input type="hidden" value="{{$customer->sale_id}}" name="customer_id">
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="product_id">प्रोडक्ट <span style="color: red">* </span></label>
                                {!! Form::select('product_id', [''=>'Select Product' ] + $product,old('product_id'), ['required','id'=>'product_id1' ,'class'=>'form-control productselect' ,'data-dropup-auto'=>"false"]) !!}
                                <div style="color: red">{{$errors->first('product_id')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-3 ">
                            <div class="form-group fg-line">
                                <label for="value">मात्रा <span style="color: red">* </span></label>
                                <input type="text" name="qty" id="qty1" value="{{old('qty')}}"
                                       required="" class="form-control number qty">
                                <div style="color: red">{{$errors->first('qty')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-3 ">
                            <div class="form-group fg-line">
                                <label for="unite">इकाई <span style="color: red">* </span></label>
                                <input type="text" name="unite"
                                       id="unite1" readonly
                                       class="form-control ">
                                <div style="color: red">{{$errors->first('unite')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="value">कीमत <span style="color: red">* </span></label>
                                <input type="text" name="price" id="price1" value="{{old('price')}}"
                                       required="" class="form-control floatnumber">
                                <div style="color: red">{{$errors->first('price')}}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 ">
                            <div class="form-group fg-line">
                                <label for="total_price">कुल कीमत <span style="color: red">* </span></label>
                                <input type="text" name="total_price" id="total_price1" value="{{old('total_price')}}"
                                       required="" class="form-control floatnumber total_price">
                                <div style="color: red">{{$errors->first('total_price')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{--model for add Payment--}}
    <div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> पेमेंट करे</h4>
                </div>
                {!! Form::open(['route' => 'addpayment','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="sale_id" class="sale_id" value="">

                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="text">पेमेंट का तरीका <span class="text-danger">*</span></label>
                                {!! Form::select("paymode", [''=>'पेमेंट का तरीका चुनें','1' => 'Cash','2' => 'Account', '3' => 'Paytm', '4' => 'Phone Pay', '5' => 'Google Pay','6'=>'Adhar Card'], 'old("paymode")', ['class'=>'selectpicker ', 'id'=>'paymode' ,'required' ,'data-dropup-auto'=>"false"]  ) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="pay_amount">पे राशि <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="payamount"
                                       class="form-control floatnumber "
                                       max="200"
                                       name="pay_amount" required
                                       value="{{old('pay_amount')}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="pay_date">पेमेंट की तिथि <span class="text-danger">*</span></label>
                                <input type='text' class="form-control created_date  "
                                       name="pay_date" id="paydate" required
                                       value="{{date('d-M-Y H:i:s')}}"/>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="remark">रिमार्क</label>
                                <input type='text' class="form-control"
                                       name="remark" id="remark"
                                       value="{{old('remark')}}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{--model for Update Payment--}}
    <div class="modal fade" id="editpayment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">अपडेट पेमेंट</h4>
                </div>
                {!! Form::open(['route' => 'updatepayment','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="pay_id" value="" class="pay_id">
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="text">पेमेंट का तरीका <span class="text-danger">*</span></label>
                                {!! Form::select("paymode", [''=>'पेमेंट का तरीका चुनें ','1' => 'Cash','2' => 'Account', '3' => 'Paytm', '4' => 'Phone Pay', '5' => 'Google Pay','6'=>'Adhar Card'], old("paymode"), ['id'=>'paymode1','class'=>'form-control productselect' ,'required' ,'data-dropup-auto'=>"false"]   ) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="pay_amount">पे राशि <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="pay_amount1"
                                       class="form-control floatnumber "
                                       max="200"
                                       name="pay_amount" required
                                       value="{{old('pay_amount')}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="pay_date">पेमेंट की तिथि <span class="text-danger">*</span></label>
                                <input type='text' class="form-control created_date "
                                       name="pay_date" id="pay_date1" required
                                       value="{{date('d-M-Y H:i:s')}}"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group fg-line">
                                <label for="remark">रिमार्क</label>
                                <input type='text' class="form-control"
                                       name="remark" id="remark1"
                                       value="{{old('remark')}}"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>


    {{--submit old due--}}
    <div class="modal fade" id="olddue" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> पुराना बकाया जमा करे</h4>
                </div>
                {!! Form::open(['route' => 'olddueAdd','method'=>'post']) !!}
                <div class="modal-body">
                    <div class="form-group fg-line">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="hidden" name="customer_id" class="customer_id" value="">
                                <label for="old_due">पुराना बकाया <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="old_due"
                                       class="form-control" readonly
                                       name="old_due"
                                       value=""/>
                            </div>
                            <div class="col-sm-6">
                                <label for="pay_amount">पे राशि <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="pay_amount_old"
                                       class="form-control floatnumber "
                                       max="200"
                                       name="pay_amount" required
                                />
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12 ">
                        <div class="form-group fg-line">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                            </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <input type="hidden" id="max_pay">
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
            format: "DD-MMM-YYYY hh:mm:ss",
        });
    </script>
    <script>
        /*product create*/
        $("#product_id").on('change', function () {
            var productId = $(this).val();
            $.ajax({
                'url': "{{route('product.getProductData')}}",
                type: 'get',
                data: {'product_id': productId},
                success: function (response) {
                    $("#price").val(response.sale_price);
                    $("#qty").val("");
                    $("#unite").val(response.unite);
                    $("#total_price").val("");
                }
            });
        });
        $("#qty").on('keyup', function () {
            var qty = $(this).val();
            var price = $("#price").val();
            var total_price = qty * price;
            $('#total_price').val(total_price);
        })
        $("#price").on('keyup', function () {
            var qty = $("#qty").val();
            var price = $(this).val();
            var total_price = price * qty;
            $('#total_price').val(total_price);

        })

        /*product edit */
        function model(e) {
            $('.dt').val(e);
            $.ajax({
                url: "{{url('admin/saleproductedit')}}",
                type: 'get',
                data: {'id': e},
                success: function (response) {
                    $("#product_id1").val(response.product_id);
                    $("#qty1").val(response.qty);
                    $("#unite1").val(response.unite);
                    $("#price1").val(response.price);
                    $("#total_price1").val(response.total_price);
                }
            });
        }


        $("#product_id1").on('change', function () {
            var productId = $(this).val();
            $.ajax({
                'url': "{{route('product.getProductData')}}",
                type: 'get',
                data: {'product_id': productId},
                success: function (response) {
                    $("#price1").val(response.sale_price);
                    $("#qty1").val("");
                    $("#unite1").val(response.unite);
                    $("#total_price1").val("");
                }
            });
        });

        $("#qty1").on('keyup', function () {
            var qty = $(this).val();
            var price = $("#price1").val();
            var total_price = qty * price;
            $('#total_price1').val(total_price);
        })

        $("#price1").on('keyup', function () {
            var qty = $("#qty1").val();
            var price = $(this).val();
            var total_price = price * qty;
            $('#total_price1').val(total_price);
        })

        //update payment
        function paymentmodel(e) {
            $('.pay_id').val(e);
            $.ajax({
                url: "{{url('admin/editpayment')}}",
                type: 'get',
                dateType: 'json',
                data: {'id': e},
                success: function (response) {
                    var data = response.data;
                    var max_pay = response.max_pay;
                    $("#paymode1").val(data.paymode);
                    $("#pay_amount1").val(data.pay_amount);
                    $("#pay_date1").val(response.pay_date);
                    $("#remark1").val(data.remark);
                    $("#max_pay").val(max_pay.total_amount);

                }
            });
        }

        function addpaymentmodel(e) {
            $('.sale_id').val(e);
            $.ajax({
                url: "{{url('admin/maxPayCustomer')}}",
                type: 'get',
                dateType: 'json',
                data: {'id': e},
                success: function (response) {

                    $("#max_pay").val(response);
                }
            });
        }

        function addoldduemodel(e) {
            $('.customer_id').val(e);
            $.ajax({
                url: "{{url('admin/olddueGet')}}",
                type: 'get',
                dateType: 'json',
                data: {'id': e},
                success: function (response) {

                    $("#old_due").val(response.old_due_balance);
                }
            });
        }

        function returnIt(id) {

            swal({
                title: "क्या आप सुनिश्चित हैं ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "हाँ, इसे रिटर्न करे!",
                CancelButtonText: "नहीं",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '{{ url('admin/saleReturn') }}/' + id,
                        type: 'post',

                        data: {
                            "_token": "{{ csrf_token() }}"
                        },

                        success: function () {
                            location.reload();
                        }

                    });
                    swal("रिटर्न!", "आपका प्रोडक्ट रिटर्न कर दिया गया है", "success");
                }
            });
        }

        function InterestSubmit(id) {
            swal({
                title: "क्या आप सुनिश्चित हैं ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "हाँ, सेल ब्याज जमा करे!",
                CancelButtonText: "नहीं",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '{{ url('admin/InterestSubmit') }}/' + id,
                        type: 'post',

                        data: {
                            "_token": "{{ csrf_token() }}"
                        },

                        success: function () {
                            location.reload();
                        }

                    });
                    swal("जमा!", "सेल ब्याज जमा किया गया", "success");

                }
            });
        }

        $('#pay_amount_old').on('input', function () {

            var value = $(this).val();
            var old_due = $('#old_due').val();

            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, old_due)));
            }
        });

        $('#payamount').on('input', function () {

            var value = $(this).val();
            var total_amount = $('#max_pay').val();


            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, total_amount)));
            }
        });

        $('#pay_amount1').on('input', function () {

            var value = $(this).val();
            var total_amount = $('#max_pay').val();


            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, total_amount)));
            }
        });
    </script>
@endpush





