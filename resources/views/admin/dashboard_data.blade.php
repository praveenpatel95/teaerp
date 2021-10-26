<div class="row">
    <div class="col-sm-6 col-md-3">
        <a href="{{route('customer.index')}}">
            <div class="mini-charts-item bgm-lightgreen">
                <div class="clearfix">
                    <div class="col-sm-4">
                        <div class="count m-l-10">
                            <h1 style="color: white ; font-size: 40px"><i class="zmdi zmdi-accounts"></i></h1>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="count ">
                            <small>ग्राहक</small>
                            <h2> {{$customer}}</h2>
                            <small class="m-t-10">
                                कुल ग्राहक<br> ₹ {{$total_customer}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{route('paidpayment.index')}}">
            <div class="mini-charts-item bgm-lightblue">
                <div class="clearfix">
                    <div class="col-sm-4">
                        <div class="count m-l-10">
                            <h1 style="color: white ; font-size: 50px"><i class="zmdi zmdi-money"></i></h1>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="count ">
                            <small>जमा राशि</small>
                            <h2>₹ {{$pay_amount}}</h2>
                            <small class="m-t-10">कुल जमा राशि <br> ₹ {{$total_pay_amount}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{route('duepayment.index')}}">
            <div class="mini-charts-item bgm-red">
                <div class="clearfix">
                    <div class="col-sm-4">
                        <div class="count m-l-10">
                            <h1 style="color: white ; font-size: 50px"><i class="zmdi zmdi-alert-circle"></i></h1>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="count ">
                            <small>बकाया राशि</small>
                            <h2>₹ {{$due_payment}}</h2>
                            <small class="m-t-10">कुल बकाया राशि <br> ₹ {{$total_due_payment}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{route('expense.index')}}">
            <div class="mini-charts-item bgm-bluegray">
                <div class="clearfix">
                    <div class="col-sm-4">
                        <div class="count m-l-10">
                            <h1 style="color: white ; font-size: 50px"><i class="zmdi zmdi-chart-donut"></i></h1>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="count ">
                            <small>खर्च राशि</small>
                            <h2>₹ {{$expense}}</h2>
                            <small class="m-t-10">कुल खर्च राशि <br> ₹ {{$total_expense}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-3">
        <a href="{{route('sale.index')}}">
            <div class="mini-charts-item bgm-deeppurple">
                <div class="clearfix">
                    <div class="col-sm-4">
                        <div class="count m-l-10">
                            <h1 style="color: white ; font-size: 50px"><i class="zmdi zmdi-square-right"></i></h1>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="count ">
                            <small>स्टॉक सेल</small>
                            <h2> {{$sale_stock}} KG</h2>
                            <small class="m-t-10">कुल स्टॉक सेल<br> {{$total_sale_stock}} KG</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{route('assignstock.index')}}">
            <div class="mini-charts-item bgm-teal">
                <div class="clearfix">
                    <div class="col-sm-4">
                        <div class="count m-l-10">
                            <h1 style="color: white ; font-size: 50px"><i class="zmdi zmdi-assignment"></i></h1>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="count ">
                            <small>असाइन स्टॉक</small>
                            <h2> {{$assign_stock}} KG</h2>
                            <small class="m-t-10">कुल असाइन स्टॉक <br> {{$total_assign_stock}} KG</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{route('returnstock.index')}}">
            <div class="mini-charts-item bgm-brown">
                <div class="clearfix">
                    <div class="col-sm-4">
                        <div class="count m-l-10">
                            <h1 style="color: white ; font-size: 50px"><i class="zmdi zmdi-assignment-return"></i></h1>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="count ">
                            <small>स्टॉक रिटर्न</small>
                            <h2> {{$stock_return}} KG</h2>
                            <small class="m-t-10">कुल स्टॉक रिटर्न<br> {{$total_stock_return}} KG</small>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-md-3">
        <a href="{{route('GiftCustomerData')}}">
            <div class="mini-charts-item bgm-pink p-b-5">
                <div class="clearfix">
                    <div class="col-sm-4">
                        <div class="count m-l-10">
                            <h1 style="color: white ; font-size: 50px"><i class="zmdi zmdi-card-giftcard"></i></h1>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="count ">
                            <small> गिफ़्ट </small>
                            <br>
                            <h2 style="color: white"><b>{{$gifts}}</b></h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

