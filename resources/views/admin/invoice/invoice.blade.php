@extends('admin.layout.app')
@section('title', 'इन्वॉइस')
@push('headerscript')
    <style>
        .btn-float {
            width: 45px !important;
            height: 45px !important;
            border-radius: 50%;
            line-height: 47px !important;
        }

        .m-btn {
            z-index: 50;
            bottom: 40px;
            right: 56px !important;
            position: fixed !important;
        }

        @page {
            size: auto;   /* auto is the initial value */
            margin: 10px !important;  /* this affects the margin in the printer settings */
        }

        @media print {
            .noprint {
                visibility: hidden;
            }

        }
    </style>
@endpush
@section('content')
    <section id="content" class="m-t-15">
        <div class="container invoice">
            <div class="block-header">
                <h2>इन्वॉइस
                </h2>
            </div>

            <div class="card">
                <div class="card-header ch-alt text-center">
                    <img class="i-logo" src="{{asset('theme/img/demo/invoice-logo.png')}}" alt="">
                </div>

                <div class="card-body card-padding">
                    <div class="row m-b-25">
                        <div class="col-xs-6">
                            <div class="text-right">
                                <p class="c-gray">Invoice from</p>

                                <h4>TeaErp</h4>

                                <span class="text-muted">
                                            <address>
                                                44, Qube Towers
                                                Dubai Media City, Dubai<br>
                                                United Arab Emirates
                                            </address>

                                            0097154686384<br/>
                                           teaerp@design.com
                                        </span>
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <div class="i-to">
                                <p class="c-gray">Invoice to</p>

                                <h4>{{$sale->customer_name}}</h4>

                                <span class="text-muted">
                                            <address>
                                                {{$sale->address}}<br>
                                                India.
                                            </address>
                                           <br/>
                                            {{$sale->mobile_no}}
                                        </span>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row m-t-25 p-0 m-b-25">
                        <div class="col-xs-3">
                            <div class="bgm-amber brd-2 p-15">
                                <div class="c-white m-b-5">Invoice No.</div>
                                <h2 class="m-0 c-white f-300">{{$sale->sale_no}}</h2>
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <div class="bgm-blue brd-2 p-15">
                                <div class="c-white m-b-5">Date</div>
                                <h2 class="m-0 c-white f-300">{{date('d-M-Y')}}</h2>
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <div class="bgm-green brd-2 p-15">
                                <div class="c-white m-b-5">Paid Amount</div>
                                <h2 class="m-0 c-white f-300">{{$pay_amount}}</h2>
                            </div>
                        </div>

                        <div class="col-xs-3">
                            <div class="bgm-red brd-2 p-15">
                                <div class="c-white m-b-5">Due Amount</div>
                                <h2 class="m-0 c-white f-300">{{$sale_total_amount-$pay_amount}}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="p-25">
                        <h4 class="c-info f-400">PRODUCT</h4>
                    </div>

                    <table class="table i-table m-t-25 m-b-25">
                        <thead class="text-uppercase">
                        <th class="c-gray">PRODUCT DESCRIPTION</th>
                        <th class="c-gray">QUANTITY</th>
                        <th class="c-gray">UNIT PRICE</th>
                        <th class="highlight">TOTAL</th>
                        </thead>

                        <tbody>
                        <thead>
                        @foreach($sale_product as $row)
                            <tr>
                                <td width="50%">
                                    <h5 class="text-uppercase f-400">{{$row->product_name}}</h5>
                                </td>

                                <td>{{$row->qty}} &nbsp{{$row->unite}}</td>
                                <td>{{$row->price}}</td>
                                <td class="highlight">₹{{$row->total_price}}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="3"></td>
                            <td class="highlight">₹{{$sale_total_amount}}</td>
                        </tr>
                        </thead>
                        </tbody>
                    </table>

                </div>


                <div class="clearfix"></div>


                <footer class="m-t-15 p-20">
                    <ul class="list-inline text-center list-unstyled">
                        <li class="m-l-5 m-r-5">
                            <small>support@teaerp.com</small>
                        </li>
                        <li class="m-l-5 m-r-5">
                            <small>93458394595</small>
                        </li>
                        <li class="m-l-5 m-r-5">
                            <small>www.teaerp.com</small>
                        </li>
                    </ul>
                </footer>
            </div>
        </div>
        <a class="btn btn-float bgm-red m-btn" data-ma-action="print" href="#"><i class="zmdi zmdi-print"></i></a>

    </section>
@endsection
@push('footerscript')
@endpush
