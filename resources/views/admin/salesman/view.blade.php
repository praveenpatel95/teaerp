@extends('admin.layout.app')
@section('title', 'सेल्समेन व्यू ')
@push('headerscript')
    <link
        href="{{url('theme/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}"
        rel="stylesheet">
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="block-header m-t-20 ">
                    <div class="col-sm-4">
                        <div class="block-header m-t-5 ">
                            <h2>सेल्समेन व्यू </h2>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="alert alert-info" role="alert">आज कुल चाय सेल : {{$today_tea}} Kg
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="alert alert-warning" role="alert">आज कुल चाय असाइन : {{$assign_tea}} Kg
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="alert alert-success" role="alert">आज कुल पेमेंट : {{$total_payment}} ₹
                        </div>
                    </div>
                    <div class="col-sm-1 ">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{route('salesman.index')}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header ch-alt" style="background: #03a2ea3b">
                            <h2>{{$salesman->name}}</h2>
                        </div>
                        <div class="card-body card-padding">
                            <div class="pmo-contact">
                                <ul>
                                    <li class="ng-binding"> पिता का नाम : {{$salesman->father_name}} </li>
                                    <li class="ng-binding"> मोबाइल नंबर : {{$salesman->mobile_no}} </li>
                                    <li class="ng-binding">पता : {{$salesman->address}}</li>
                                    <li class="ng-binding">आधार न.: {{$salesman->adhar_no}}</li>
                                    <li class="ng-binding">वेतन प्रकार : {{$salesman->salary_type}}</li>
                                    <li class="ng-binding">वेतन राशि : {{$salesman->salary_amount}}</li>
                                    <li class="ng-binding">जोइनिंग
                                        तारीख़ : {{date('d-M-Y',strtotime($salesman->joining_date))}}</li>
                                    <li class="ng-binding">स्थिति:-@if($salesman->status==1) सक्रिय @else
                                            निष्क्रिय @endif</li>
                                    <li class="ng-binding">लाइन : {{$salesman->line_name}}</li>
                                    <li class="ng-binding">कुल सेल कमीशन : {{$commission}}</li>
                                    <li class="ng-binding">कुल पेमेंट कमीशन : {{$payment_commission}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <ul class="tab-nav tn-justified tn-icon" role="tablist">
                                <li role="presentation" class="active">
                                    <a class="col-sx-4" href="#tab-1" aria-controls="tab-1" role="tab"
                                       data-toggle="tab">
                                        प्रोडक्ट सेल हिस्ट्री
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
                                    <a class="col-xs-4" href="#tab-3" aria-controls="tab-3" role="tab"
                                       data-toggle="tab">
                                        लॉग-इन डिटैल
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-20">
                                <div role="tabpanel" class="tab-pane animated fadeIn in active" id="tab-1">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-bordered m-0">
                                                <tbody>
                                                <tr>
                                                    <th>#</th>
                                                    <th>ग्राहक का नाम</th>
                                                    <th>सेल न.</th>
                                                    <th>सेल की तिथी</th>
                                                    <th>प्रोडक्ट</th>
                                                    <th>मात्रा</th>
                                                    <th>कीमत</th>
                                                    <th>कुल कीमत</th>
                                                   {{-- <th>कमीशन</th>--}}
                                                </tr>
                                                @foreach($productData as $row)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$row->customer_name}}</td>
                                                        <td>{{$row->sale_no}}</td>
                                                        <td>{{date('j M, Y h:ia',strtotime($row->sale_date))}}</td>
                                                        <td>{{$row->product_name}}</td>
                                                        <td>{{$row->qty}} {{$row->unite}}</td>
                                                        <td>{{$row->price}}</td>
                                                        <td>{{$row->total_price}}</td>
                                                       {{-- <td>{{$row->sale_commission}}</td>--}}
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane animated fadeIn in " id="tab-4">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-bordered m-0">
                                                <tbody>
                                                <tr>
                                                    <th>ग्राहक का नाम</th>
                                                    <th>सेल न.</th>
                                                    <th>रिटर्न की तिथी</th>
                                                    <th>प्रोडक्ट</th>
                                                    <th>मात्रा</th>
                                                    <th>कीमत</th>
                                                    <th>कुल कीमत</th>
                                                </tr>
                                                @foreach($salesman_product_return as $row)
                                                    <tr>
                                                        <td>{{$row->customer_name}}</td>
                                                        <td>{{$row->sale_no}}</td>
                                                        <td>{{date('j M, Y h:ia',strtotime($row->updated_at))}}</td>
                                                        <td>{{$row->product_name}}</td>
                                                        <td>{{$row->qty}} &nbsp{{$row->unite}}</td>
                                                        <td>{{$row->price}}</td>
                                                        <td>{{$row->total_price}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane animated fadeIn" id="tab-2">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-bordered m-0">
                                                <tbody>
                                                <tr>
                                                    <th>#</th>
                                                    <th>ग्राहक का नाम</th>
                                                    <th>सेल न.</th>
                                                    <th>पेमेंट का तरीका</th>
                                                    <th>जमा राशि</th>
                                                    <th>पेमेंट की तिथि</th>
                                                    <th>रिमार्क</th>
                                                </tr>
                                                @foreach($paymentData as $row)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$row->customer_name}}</td>
                                                        <td>{{$row->sale_no}}</td>
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
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane animated fadeIn in " id="tab-3">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h5><b>लॉग-इन डिटैल</b></h5>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group fg-line">
                                                <label for="email">ईमेल </label>
                                                <input type="email" id="email" value="{{$salesman->email}}"
                                                       class="form-control" name="email" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group fg-line">
                                                <label for="oldpassword">Old पासवर्ड</label>
                                                <input type="hidden" name="general" value="2">
                                                <input type="text" id="oldpassword" value="{{$salesman->password}}"
                                                       class="form-control" name="oldpassword" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <h5><b>पासवर्ड बदलें</b></h5>
                                        </div>
                                        {!! Form::open(['route'=>array('SalesmanPasswordUpdate',$salesman->id),'method'=>'post','onsubmit'=>'submitBtn.disabled = true','return'=>true]) !!}
                                        <div class="col-sm-6 ">
                                            <div class="form-group fg-line">
                                                <label for="password">New पासवर्ड <span
                                                        class="text-danger">*</span></label>
                                                <input type="password" id="password"
                                                       class="form-control" name="password" required>
                                                <div class="text-danger">{{$errors->first('password')}}</div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group fg-line">
                                                <label for="password_confirmation">पासवर्ड की पुष्टि करें <span
                                                        class="text-danger">*</span></label>
                                                <input type="password" id="c_password"
                                                       class="form-control" name="c_password"
                                                       required>
                                                <div
                                                    class="text-danger">{{$errors->first('password_confirmation')}}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group fg-line m-b-5">
                                                <button type="submit" id="submitBtn" class="btn btn-primary ">सेव
                                                </button>
                                                &nbsp&nbsp&nbsp
                                                <button type="reset" class="btn btn-default ">रीसेट</button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('footerscript')

@endpush





