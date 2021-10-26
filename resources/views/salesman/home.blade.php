@extends('salesman.layout.app')
@section('title','Dashboard')

@section('content')
    <section id="content">
        <div class="container">
            <div class="row m-b-10">
                <div class="col-sm-12">
                    <div class="block-header m-t-20 m-l-5">
                        <div class="block-header m-t-20 ">
                            <h2>Dashboard </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="mini-charts">
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-lightblue">
                                    <div class="clearfix">
                                        <div class="count">
                                            <div class="chart stats-bar "></div>
                                            <small>Total Sale</small>
                                            <h2>₹ {{$total_sale}}</h2>

                                            <small class="m-t-10">This Month<br> ₹ {{$total_sale_by_month}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-red">
                                    <div class="clearfix">
                                        <div class="count">
                                            <div class="chart stats-line-2"></div>
                                            <small>Due Payment</small>
                                            <h2>₹ {{$due_payment}}</h2>
                                            <small class="m-t-10">This Month<br> ₹ {{$due_payment_by_month}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-lightgreen">
                                    <div class="clearfix">
                                        <div class="count">
                                            <div class="chart stats-line"></div>
                                            <small>Total Custome</small>
                                            <h2>₹ {{$total_customer}}</h2>
                                            <small class="m-t-10">This Month<br> ₹ {{$total_customer_by_month}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="mini-charts-item bgm-bluegray">
                                    <div class="clearfix">
                                        <div class="count">
                                            <div class="chart stats-bar "></div>
                                            <small>Salary</small>
                                            <h2>₹ {{$salemsan->salary_amount}}</h2>

                                            <small class="m-t-10">Salary Type<br> {{$salemsan->salary_type}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>Latest Sale </h2>
                                    </div>
                                    <div class="table-responsive">
                                        <div class="card-body">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Customer</th>
                                                    <th>Sale No.</th>
                                                    <th>Sale Date</th>
                                                    <th>Total Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($latest_sale as $row)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>

                                                        <td>{{$row->customer_name}}</td>
                                                        <td>{{$row->sale_no}}</td>
                                                        <td>{{date('d-M-Y',strtotime($row->sale_date))}}</td>
                                                        <td>{{$row->total_amount}}</td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <a class="view-more" href="{{route('salesmansale.index')}}">View All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>Latest Payment </h2>
                                    </div>
                                    <div class="table-responsive">
                                        <div class="card-body">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Customer</th>
                                                    <th>Pay Mode</th>
                                                    <th>Pay Date</th>
                                                    <th>Paid Amount</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($latest_payment as $row)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$row->customer_name}}</td>
                                                        <td>
                                                            @if ($row->paymode == 0)
                                                                cash
                                                            @elseif ($row->paymode == 1)
                                                                Account
                                                            @elseif ($row->paymode == 2)
                                                                Paytm
                                                            @elseif ($row->paymode == 3)
                                                                Phone Pay
                                                            @elseif ($row->paymode == 4)
                                                                Google Pay
                                                            @else
                                                                Adhar Card
                                                            @endif
                                                        </td>
                                                        <td>{{date('d-M-Y',strtotime($row->pay_date))}}</td>
                                                        <td>{{$row->pay_amount}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <a class="view-more" href="{{route('salesmanpaidpayment.index')}}">View
                                                All</a>
                                        </div>
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
