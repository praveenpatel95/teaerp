<style>
    .hi-menu > li .dropdown-menu {
        top: 42px !important;
    }
    .dropdown-menu {
        top: 40px !important;
    }
</style>
<header id="header-alt" class="clearfix noprint" data-ma-theme="lightblue">
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
            <a href="{{route('admin.home')}}">
                टी ERP</a>
        </li>
        <li class="pull-right">
            <ul class="hi-menu">
                <li class="dropdown noprint">
                    <a data-toggle="dropdown" href="#">{{ Auth::user()->name }} &nbsp<img
                            src="{{asset('theme/img/demo/profile-pics/1.png')}}" alt="profile"
                            style="border-radius: 40px; width: 30px"></a>
                    <ul class="dropdown-menu dm-icon pull-right p-t-10">
                        <li>
                            <a href="{{route('admin.profile')}}"><i class="zmdi zmdi-account"></i> प्रोफ़ाइल</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="zmdi zmdi-time-restore"></i>लॉग आउट</a>
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
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('admin.home') ? 'active' : '' }} "><a
                    href="{{route('admin.home')}}"><i class="zmdi zmdi-home"></i> होम</a></li>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('linehisab') ? 'active' : '' }} "><a
                    href="{{route('linehisab')}}"><i class="zmdi zmdi-check-circle"></i> लाइन हिसाब</a></li>
            <li class="dropdown {{\Illuminate\Support\Facades\Request::routeIs('salesman.index') ? 'active' : '' }} {{\Illuminate\Support\Facades\Request::routeIs('attendance.index') ? 'active' : '' }}">
                <a data-toggle="dropdown" href="#"><i class="zmdi zmdi-accounts"></i> सेल्समेन</a>
                <ul class="dropdown-menu dm-icon ">
                    <li class=""><a href="{{route('salesman.index')}}"><i class="zmdi zmdi-accounts"></i> सेल्समेन लिस्ट</a>
                    </li>
                    <li class=""><a href="{{route('attendance.index')}}"><i class="zmdi zmdi-accounts-list-alt"></i>
                            अटेंडेंस</a></li>
                    <li class=""><a href="{{route('advancepayment.index')}}"><i class="zmdi zmdi-paypal-alt"></i>
                            एडवांस पेमेंट</a></li>
                    <li class=""><a href="{{route('salesmanSalary.index')}}"><i class="zmdi zmdi-money"></i>
                            सेल्समेन वेतन</a></li>
                </ul>
            </li>
            <li class="dropdown {{\Illuminate\Support\Facades\Request::routeIs('customer.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('sale.index') ? 'active' : '' }}">
                <a data-toggle="dropdown" href="#"><i class="zmdi zmdi-accounts"></i> ग्राहक</a>
                <ul class="dropdown-menu dm-icon ">
                    <li class=""><a href="{{route('customer.index')}}"><i class="zmdi zmdi-format-indent-increase"></i>
                            ग्राहक लिस्ट</a></li>
                    <li class=""><a href="{{route('sale.index')}}"><i class="zmdi zmdi-shopping-cart-add"></i> सेल लिस्ट</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown {{\Illuminate\Support\Facades\Request::routeIs('instock.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('assignstock.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('returnstock.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('purchase.index') ? 'active' : '' }}">
                <a data-toggle="dropdown" href="#"><i class="zmdi zmdi-shopping-basket"></i> स्टॉक</a>
                <ul class="dropdown-menu dm-icon ">
                    <li class=""><a href="{{route('instock.index')}}"><i class="zmdi zmdi-format-indent-increase"></i>
                            इन स्टॉक</a></li>
                    <li class=""><a href="{{route('assignstock.index')}}"><i class="zmdi zmdi-shopping-cart-plus"></i>
                            असाइन स्टॉक</a></li>
                    <li class=""><a href="{{route('returnstock.index')}}"><i class="zmdi zmdi-assignment-return"></i>
                            रिटर्न स्टॉक</a></li>
                    <li class=""><a href="{{route('purchase.index')}}"><i class="zmdi zmdi-collection-add"></i> पर्चेज
                        </a></li>
                </ul>
            </li>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('expense.index') ? 'active' : '' }}"><a
                    href="{{route('expense.index')}}"><i class="zmdi zmdi-dehaze"></i>
                    खर्च</a></li>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('GiftCustomerData') ? 'active' : '' }}"><a
                    href="{{route('GiftCustomerData')}}"><i class="zmdi zmdi-card-giftcard"></i>
                    गिफ्ट</a></li>
            <li class="{{\Illuminate\Support\Facades\Request::routeIs('saleinterest.index') ? 'active' : '' }} "><a
                    href="{{route('saleinterest.index')}}"><i class="zmdi zmdi-info"></i> सेल ब्याज</a></li>
            <li class="dropdown {{\Illuminate\Support\Facades\Request::routeIs('productReport') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('paymenthistory.index') ? 'active' : '' }}">
                <a data-toggle="dropdown" href="#"><i class="zmdi zmdi-equalizer"></i> रिपोर्ट</a>
                <ul class="dropdown-menu dm-icon ">
                    <li class=""><a href="{{route('productReport')}}"><i class="zmdi zmdi-blur-linear"></i> प्रोडक्ट आइटम रिपोर्ट</a></li>
                    <li class=""><a href="{{route('paidpayment.index')}}"><i class="zmdi zmdi-paypal"></i> जमा राशि </a>
                    </li>
                    <li class=""><a href="{{route('duepayment.index')}}"><i class="zmdi zmdi-disqus"></i> बकाया राशि
                        </a></li>
                </ul>
            </li>
            <li class="dropdown {{\Illuminate\Support\Facades\Request::routeIs('product.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('routes.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('line.index') ? 'active' : '' }}">
                <a data-toggle="dropdown" href="#"><i class="zmdi zmdi-settings"></i> सेटिंग</a>
                <ul class="dropdown-menu dm-icon ">
                    <li class=""><a href="{{route('product.index')}}"><i class="zmdi zmdi-present-to-all"></i> प्रोडक्ट</a>
                    </li>
                    <li class=""><a href="{{route('routes.index')}}"><i class="zmdi zmdi-router"></i> रूट</a></li>
                    <li class=""><a href="{{route('line.index')}}"><i class="zmdi zmdi-format-line-spacing"></i>
                            लाइन</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<!-- Mobile Menu -->
<aside id="sidebar" class="sidebar sidebar-alt c-overflow">
    <ul class="main-menu">
        <li class="{{\Illuminate\Support\Facades\Request::routeIs('admin.home') ? 'active' : '' }} "><a
                href="{{route('admin.home')}}"><i class="zmdi zmdi-home"></i> होम</a></li>
        <li class="{{\Illuminate\Support\Facades\Request::routeIs('linehisab') ? 'active' : '' }} "><a
                href="{{route('linehisab')}}"><i class="zmdi zmdi-check-circle"></i> लाइन हिसाब</a></li>
        <li class="sub-menu {{\Illuminate\Support\Facades\Request::routeIs('salesman.index') ? 'active' : '' }}
        {{\Illuminate\Support\Facades\Request::routeIs('attendance.index') ? 'active' : '' }}
        {{\Illuminate\Support\Facades\Request::routeIs('advancepayment.index') ? 'active' : '' }}
        {{\Illuminate\Support\Facades\Request::routeIs('salesmanSalary.index') ? 'active' : '' }}">
            <a data-ma-action="submenu-toggle" href="#"><i class="zmdi zmdi-account"></i> सेल्समेन</a>
            <ul>
                <li class=""><a href="{{route('salesman.index')}}"> सेल्समेन लिस्ट</a></li>
                <li class=""><a href="{{route('attendance.index')}}"> अटेंडेंस</a></li>
                <li class=""><a href="{{route('advancepayment.index')}}">एडवांस पेमेंट</a></li>
                <li class=""><a href="{{route('salesmanSalary.index')}}">सेल्समेन वेतन</a></li>
            </ul>
        </li>
        <li class="sub-menu {{\Illuminate\Support\Facades\Request::routeIs('customer.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('assignstock.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('returnstock.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('purchase.index') ? 'active' : '' }} {{\Illuminate\Support\Facades\Request::routeIs('sale.index') ? 'active' : '' }} ">
            <a data-ma-action="submenu-toggle" href="#"><i class="zmdi zmdi-accounts"></i> ग्राहक</a>
            <ul>
                <li class=""><a href="{{route('customer.index')}}"> ग्राहक लिस्ट</a></li>
                <li class=""><a href="{{route('sale.index')}}"> सेल लिस्ट</a></li>
            </ul>
        </li>
        <li class="sub-menu {{\Illuminate\Support\Facades\Request::routeIs('assignstock.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('line.index') ? 'active' : '' }}">
            <a data-ma-action="submenu-toggle" href="#"><i class="zmdi zmdi-shopping-basket"></i> स्टॉक</a>
            <ul>
                <li class=""><a href="{{route('instock.index')}}">
                        इन स्टॉक</a></li>
                <li class=""><a href="{{route('assignstock.index')}}">
                        असाइन स्टॉक</a></li>
                <li class=""><a href="{{route('returnstock.index')}}">
                        रिटर्न स्टॉक</a></li>
                <li class=""><a href="{{route('purchase.index')}}"> पर्चेज
                    </a></li>
            </ul>
        </li>

        <li class="{{\Illuminate\Support\Facades\Request::routeIs('expense.index') ? 'active' : '' }}"><a
                href="{{route('expense.index')}}"><i class="zmdi zmdi-dehaze"></i> खर्च</a></li>

        <li class="{{\Illuminate\Support\Facades\Request::routeIs('GiftCustomerData') ? 'active' : '' }}"><a
                href="{{route('GiftCustomerData')}}"><i class="zmdi zmdi-card-giftcard"></i> गिफ्ट </a></li>

        <li class="sub-menu {{\Illuminate\Support\Facades\Request::routeIs('productReport') ? 'active' : '' }}">
            <a href="#" data-ma-action="submenu-toggle"><i class="zmdi zmdi-equalizer"></i> रिपोर्ट</a>
            <ul>
                <li class=""><a href="{{route('productReport')}}"> प्रोडक्ट आइटम रिपोर्ट</a>
                </li>
                <li class=""><a href="{{route('paidpayment.index')}}">जमा राशि </a></li>
                <li class=""><a href="{{route('duepayment.index')}}"> बकाया राशि</a></li>
            </ul>
        </li>

        <li class="sub-menu  {{\Illuminate\Support\Facades\Request::routeIs('routes.index') ? 'active' : '' }}{{\Illuminate\Support\Facades\Request::routeIs('line.index') ? 'active' : '' }}">
            <a href="#" data-ma-action="submenu-toggle"><i class="zmdi zmdi-settings"></i> सेटिंग</a>
            <ul>
                <li class=""><a href="{{route('product.index')}}"> प्रोडक्ट</a>
                </li>
                <li class=""><a href="{{route('routes.index')}}"> रूट</a></li>
                <li class=""><a href="{{route('line.index')}}">
                        लाइन</a></li>
            </ul>
        </li>
    </ul>
</aside>
<!-- End Mobile Menu -->
