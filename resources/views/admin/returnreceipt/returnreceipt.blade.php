@extends('admin.layout.app')
@section('title', 'रिटर्न  रसीद')
@push('headerscript')
    <style>
        @page {
            size: auto;   /* auto is the initial value */
            /* this affects the margin in the printer settings */
        }

        @media print {
            .noprint {
                display: none !important;
            }
            #main{padding: 0 !important;}
        }


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
    </style>
@endpush
@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10 noprint">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="btn-group pull-right">
                            <a class="btn btn-primary btn-block" href="{{url()->previous()}}"><i
                                    class="zmdi zmdi-long-arrow-return"> </i> पीछे जाए</a>
                        </div>
                        <div class="block-header m-t-20 ">
                            <h2>रिटर्न  रसीद</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body card-padding ">
                            <section>
                                <div class="card-body">
                                    <!-- Payment Receipt Company Details -->
                                    <div class="row">
                                        <div class="col-sm-5 col-12 ">
                                            <div class="col-12 media ">
                                                <img src="{{asset('theme/img/demo/invoice-logo.png')}}"
                                                     alt="teaerp logo logo"
                                                     width="80px">
                                            </div>
                                            <hr>
                                            <div class="col-12 media mt-1">

                                                <address style="font-size: 16px;">
                                                    Tea erp Udaipur - 313001 (Raj.)<br>
                                                    India<br>
                                                    <i class="zmdi zmdi-account-box-mail"></i>&nbsp; teaerp@gmail.com
                                                    &nbsp;<i
                                                        class="zmdi zmdi-globe  "></i>&nbsp; www.teaerp.com<br>
                                                </address>

                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-12">
                                            <span style="font-size:15px"><i
                                                    class="zmdi zmdi-phone"></i> +91 999993999</span><br>
                                        </div>
                                        <div class="col-sm-3 col-12 pull-right">
                                            <span
                                                style="font-size:15px">Return Receipt No. {{$sale_product->return_rec_no}}</span><br>

                                            <hr>

                                        </div>
                                    </div>
                                    <!--/ Invoice Company Details -->

                                    <!-- Invoice Recipient Details -->
                                    <div class="row pt-3">
                                        <div class="col-sm-12 col-12 text-left">
                                            <h4 class=""
                                                style="color: grey; line-height: 3.30rem; font-family: Adobe Caslon Pro; font-size: 19px">
                                                Returned with
                                                thanks from M/s. <span
                                                    style="font-size: 20px; text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$customer->customer_name}}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                               &nbsp; &nbsp; Product <span
                                                    style="font-size: 20px; text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$sale_product->product_name}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                Quantity <span
                                                    style="font-size: 20px; text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;{{$sale_product->qty}}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                &nbsp; &nbsp;   of Rupees <span  style="font-size: 20px; text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;{{$sale_product->total_price}}&nbsp;&nbsp;&nbsp;&nbsp;
                                                  </span> &nbsp; &nbsp;
                                               On  Date:<span
                                                    style="font-size: 20px; text-decoration: underline">&nbsp;&nbsp;&nbsp;&nbsp;{{date('d-M-Y',strtotime($sale_product->updated_at))}} &nbsp;</span>.
                                            </h4>
                                        </div>
                                    </div>
                                    <!--/ Invoice Recipient Details -->

                                    <!-- Invoice Items Details -->
                                    <div id="invoice-items-details" class="row pt-1 ">
                                        <div class="container-fluid">

                                        </div>
                                    </div>
                                    <div id="invoice-footer" class="row m-t-30 mb-0">

                                        <div class="col-sm-2 pull-right">
                                            <span class="text-right" style="font-size: 15px"><strong>Authorised Signature</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
            <a class="btn btn-float bgm-red m-btn" data-ma-action="print" href="#"><i class="zmdi zmdi-print"></i></a>
        </div>
    </section>
@endsection
@push('footerscript')
@endpush
