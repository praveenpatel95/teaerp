<style>
    .hi-menu > li .dropdown-menu {
        top: 42px !important;
    }

    .dropdown-menu {
        top: 40px !important;
    }
</style>
<header id="header-alt" class="clearfix" data-ma-theme="lightblue">
    <!-- Make sure to change both class and data-current-skin when switching sking manually -->
    <ul class="h-inner clearfix">
        <li class="hi-trigger ma-trigger" data-ma-action="sidebar-open" data-ma-target="#sidebar">
            <div class="line-wrap">
                <div class="line top"></div>
                <div class="line center"></div>
                <div class="line bottom"></div>
            </div>
        </li>

        <li class="hi-logo hidden-xs">
            <a href="{{route('salesman.home')}}">Tea ERP</a>
        </li>

        <li class="pull-right">
            <ul class="hi-menu">
                <li class="dropdown noprint">
                    <a data-toggle="dropdown" href="#">{{ Auth::user()->name }} &nbsp<img
                            src="{{asset('theme/img/demo/profile-pics/1.png')}}" alt="profile"
                            style="border-radius: 40px; width: 30px"></a>
                    <ul class="dropdown-menu dm-icon pull-right p-t-10">
                        <li>
                            <a href="{{route('salesman.profile')}}"><i class="zmdi zmdi-account"></i>Profile</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="zmdi zmdi-time-restore"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
    <nav class="ha-menu">
        <ul>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesman.home') ? 'active' : '' }} "><a
                    href="{{route('salesman.home')}}"><i class="zmdi zmdi-home"></i> Home</a></li>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesmancustomer.index') ? 'active' : '' }} "><a
                    href="{{route('salesmancustomer.index')}}"><i class="zmdi zmdi-accounts"></i> Customer</a></li>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesmansale.index') ? 'active' : '' }} "><a
                    href="{{route('salesmansale.index')}}"><i class="zmdi zmdi-shopping-cart-add"></i> Sale </a></li>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesmanpaidpayment.index') ? 'active' : '' }} "><a
                    href="{{route('salesmanpaidpayment.index')}}"><i class="zmdi zmdi-paypal"></i> Payments</a></li>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesmanduepayment.index') ? 'active' : '' }} "><a
                    href="{{route('salesmanduepayment.index')}}"><i class="zmdi zmdi-disqus"></i> Due Payments</a></li>
        </ul>


    </nav>


</header>
<!-- Mobile Menu -->

<aside id="sidebar" class="sidebar sidebar-alt c-overflow">


    <ul class="main-menu">
        <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesman.home') ? 'active' : '' }} "><a href="{{route('salesman.home')}}"><i class="zmdi zmdi-home"></i> Home</a></li>
        <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesmancustomer.index') ? 'active' : '' }} "><a href="{{route('salesmancustomer.index')}}"><i class="zmdi zmdi-accounts"></i> Customer </a></li>
        <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesmansale.index') ? 'active' : '' }} "><a href="{{route('salesmansale.index')}}"><i class="zmdi zmdi-shopping-cart-add"></i> Sale </a></li>
        <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesmanpaidpayment.index') ? 'active' : '' }} "><a href="{{route('salesmanpaidpayment.index')}}"><i class="zmdi zmdi-paypal"></i> Paid Payment</a></li>
        <li class="{{\Illuminate\Support\Facades\Request::routeIs('salesmanduepayment.index') ? 'active' : '' }} "><a href="{{route('salesmanduepayment.index')}}"><i class="zmdi zmdi-disqus"></i> Due Payment</a></li>
    </ul>
</aside>
<!-- End Mobile Menu -->
