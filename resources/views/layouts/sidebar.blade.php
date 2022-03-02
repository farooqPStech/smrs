@extends('layouts.app')

@section('sidebar')
    @include('sweetalert::alert')
    <ul class="navbar-nav bg-gradient-grey sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
            <div class="sidebar-brand-icon">
                <!--rotate-n-15-->
                <!-- <i class="fas fa-water"></i> -->
                <img src="{{ asset('img/brand/icon.png') }}" class="sidebar-icon">
            </div>
            <!-- <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Laravel') }}</div> -->
            <div class="sidebar-brand-text mx-3">SMRS</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="/home">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            MAIN MENU
        </div>

        <!-- Nav Item - Send Parcel Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseParcel" aria-expanded="true"
                aria-controls="collapseParcel">
                <i class="fas fa-route"></i>
                <span>Route</span>
            </a>
            <div id="collapseParcel" class="collapse" aria-labelledby="headingParcel" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- <h6 class="collapse-header">Send parcel:</h6> -->
                    <a class="collapse-item" href="/route/list">Route List</a>
                    <a class="collapse-item" href="/route/assignroute">Assign Route</a>
                    <a class="collapse-item" href="/route/adjustroute">Adjust Route</a>
                    <!-- <a class="collapse-item" href="/parcel/quicksendparcel">QuickSend Parcel</a> -->
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="/consumer/list">
                <i class="fa fa-user-friends"></i>
                <span>Consumer</span>
            </a>
            <!--  <div id="collapseAllParcel" class="collapse" aria-labelledby="headingAllParcel"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                         <h6 class="collapse-header">All Parcel:</h6>
                        <a class="collapse-item" href="/parcel/customerlist">List</a>
                         <a class="collapse-item" href="/parcel/status">All Parcel</a>
                         <a class="collapse-item" href="/parcel/report">Parcel Report</a>
                    </div>
                </div> -->
        </li>

        @if (auth()->user()->type == 9)


            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('route_assign') }}">
                    <i class="fa fa-user"></i>
                    <span>Assign Route</span>
                </a>
            </li>
        @endif


        {{-- <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('meterlist') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Meter</span>
            </a> --}}
            <!--  <div id="collapseAllParcel" class="collapse" aria-labelledby="headingAllParcel"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                         <h6 class="collapse-header">All Parcel:</h6>
                        <a class="collapse-item" href="/parcel/customerlist">List</a>
                         <a class="collapse-item" href="/parcel/status">All Parcel</a>
                         <a class="collapse-item" href="/parcel/report">Parcel Report</a>
                    </div>
                </div> -->
        {{-- </li> --}}

        <!-- Nav Item - Referral Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="/handheld/list">
                <i class="fas fa-tablet-alt"></i>
                <span>Handheld</span>
            </a>
            <!--  <div id="collapseReferral" class="collapse" aria-labelledby="headingReferral"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="/vendor/vendorlist">List</a>
                        <a class="collapse-item" href="/vendor/unpaid">Unpaid</a>
                        <a class="collapse-item" href="/vendor/pending">Pending</a>
                        <a class="collapse-item" href="/vendor/approved">Approved</a>
                        <a class="collapse-item" href="/vendor/paid">Paid</a>
                        <a class="collapse-item" href="/vendor/rejected">Rejected</a>
                    </div>
                </div> -->
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="/printer/list">
                <i class="fas fa-tablet-alt"></i>
                <span>Printer</span>
            </a>
            <!--  <div id="collapseReferral" class="collapse" aria-labelledby="headingReferral"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="/vendor/vendorlist">List</a>
                        <a class="collapse-item" href="/vendor/unpaid">Unpaid</a>
                        <a class="collapse-item" href="/vendor/pending">Pending</a>
                        <a class="collapse-item" href="/vendor/approved">Approved</a>
                        <a class="collapse-item" href="/vendor/paid">Paid</a>
                        <a class="collapse-item" href="/vendor/rejected">Rejected</a>
                    </div>
                </div> -->
        </li>

        <!-- Nav Item - Account Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="/user/list">
                <i class="fa fa-user-friends"></i>
                <span>User</span>
            </a>
            <!-- <div id="collapseAccount" class="collapse" aria-labelledby="headingAccount"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Account:</h6>
                        <a class="collapse-item" href="/account/profile">Profile</a>
                        <a class="collapse-item" href="/account/addressbook"> All Address Book</a>
                        <a class="collapse-item" href="/account/transactionhistory">Transaction History</a>
                        <a class="collapse-item" href="/account/autotopup">Auto Top Up</a>
                        <a class="collapse-item" href="/account/awbformat">AWB Format</a>
                        <a class="collapse-item" href="/account/statement">Statement</a>
                    </div>
                </div> -->
        </li>

        <!-- Nav Item - Shop Collapse Menu -->
        <!--         <li class="nav-item">
                <a class="nav-link collapsed" href="/report/list">
                    <i class="far fa-file"></i>
                    <span>Report</span>
                </a>
                <div id="collapseShop" class="collapse" aria-labelledby="headingShop"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="/shop/buy">Buy</a>
                        <a class="collapse-item" href="/shop/orderhistory">Order History</a>
                        <a class="collapse-item" href="/shop/redeem">Redeem (Flyer Request)</a>
                    </div>
                </div>
            </li> -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="/log/list">
                <i class="fas fa-list"></i>
                <span>Log</span>
            </a>
            <!--  <div id="collapseShop" class="collapse" aria-labelledby="headingShop"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="/shop/buy">Buy</a>
                        <a class="collapse-item" href="/shop/orderhistory">Order History</a>
                        <a class="collapse-item" href="/shop/redeem">Redeem (Flyer Request)</a>
                    </div>
                </div> -->
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="/broadcast/list">
                <i class="far fa-comment-alt"></i>
                <span>Message Broadcast</span>
            </a>
            <!--             <div id="collapseShop" class="collapse" aria-labelledby="headingShop"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="/shop/buy">Buy</a>
                        <a class="collapse-item" href="/shop/orderhistory">Order History</a>
                        <a class="collapse-item" href="/shop/redeem">Redeem (Flyer Request)</a>
                    </div>
                </div> -->
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport"
                aria-expanded="true" aria-controls="collapseReport">
                <i class="far fa-comment-alt"></i>
                <span>Report</span>
            </a>
            <div id="collapseReport" class="collapse" aria-labelledby="headingShop" data-parent="#accordionSidebar">
                <span style="color: white;">Industrial</span>
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- <a class="collapse-item" href="/report/billingdetailsbyroute">Billing Details By Route</a> -->
                    <!-- <a class="collapse-item" style="border-top: 1px solid black;" href="/report/billingdetailssummarybyroute">Billing Details Summary<br> By Route</a> -->
                    <!-- <a class="collapse-item" style="border-top: 1px solid black;" href="/report/cancelbilllisting">Cancel Bill Listing</a> -->
                    <!-- <a class="collapse-item" style="border-top: 1px solid black;" href="/report/billingdetailsbyroutegmsb">Billing Details By Route<br> - GMSB</a> -->
                    <!-- <a class="collapse-item" style="border-top: 1px solid black;" href="/report/kivbilllisting">KIV Bill Listing</a> -->
                    <a class="collapse-item" href="/report/loginlogout">Login/Logout</a>
                    <a class="collapse-item" style="border-top: 1px solid black;" href="/report/meterrangecheck">Meter
                        Range Check</a>
                    <a class="collapse-item" style="border-top: 1px solid black;"
                        href="/report/meterrangechecksummary">Meter Range Check <br>Summary</a>
                    <!-- <a class="collapse-item" style="border-top: 1px solid black;" href="/report/dailysalesperformancebyroute">Daily Sales Performance <br>By Route</a> -->
                    <!-- <a class="collapse-item" style="border-top: 1px solid black;" href="/report/dailysalesperformancebytariff">Daily Sales Performance <br>By Tariff</a> -->
                    <a class="collapse-item" style="border-top: 1px solid black;" href="/report/readinginterval">Reading
                        Interval</a>
                    <a class="collapse-item" style="border-top: 1px solid black;"
                        href="/report/readingintervalsummary">Reading Interval <br>Summary</a>
                </div>
            </div>
            <div id="collapseReport" class="collapse" aria-labelledby="headingShop" data-parent="#accordionSidebar">
                <span style="color: white;">Resident</span>
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/report/loginlogoutresident">Login/Logout</a>
                    <a class="collapse-item" style="border-top: 1px solid black;"
                        href="/report/billingdetailsbyamountresident">Billing Details By Amount</a>
                    <a class="collapse-item" style="border-top: 1px solid black;"
                        href="/report/billingdetailsbyrouteresident">Billing Details By Route</a>
                    <a class="collapse-item" style="border-top: 1px solid black;"
                        href="/report/billingdetailssummarybyrouteresident">Billing Details Summary<br>By Route</a>
                    <a class="collapse-item" style="border-top: 1px solid black;"
                        href="/report/billingdetailsbyroutegmsbresident">Billing Details<br> By Route - GMSB </a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Tools Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTools" aria-expanded="true"
                aria-controls="collapseTools">
                <i class="fas fa-fw fa-tools"></i>
                <span>Setting</span>
            </a>
            <div id="collapseTools" class="collapse" aria-labelledby="headingTools" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- <h6 class="collapse-header">Tools:</h6> -->
                    <a class="collapse-item" href="/setting/appversion">App Version</a>
                    <a class="collapse-item" href="/setting/duedate">Due Date</a>
                    <a class="collapse-item" href="/setting/billnote">Bill Note</a>
                    <a class="collapse-item" href="/route/routeassignment">Route Assign</a>
                    <a class="collapse-item" href="/setting/info">Company Information</a>
                    <a class="collapse-item" href="/setting/branch">Branch</a>
                    <a class="collapse-item" href="/setting/issuecode">Issue Code</a>
                    <a class="collapse-item" href="/setting/obstaclecode">Obstacle Code</a>
                    <a class="collapse-item" href="/setting/rejectcode">Reject Code</a>
                    <a class="collapse-item" href="/setting/meterlocation">Meter Location</a>
                    <a class="collapse-item" href="/setting/highlow">High Low Control</a>
                    <a class="collapse-item" href="/setting/price">Price</a>
                    <a class="collapse-item" href="/setting/billing">Billing Days</a>
                    <a class="collapse-item" href="/setting/gcpt">GCPT</a>
                    <a class="collapse-item" href="/setting/file">File Management</a>
                    <a class="collapse-item" href="/setting/printables">BIll Printable</a>
                    <a class="collapse-item" href="/setting/consumptionvariant">Consumption Variant</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

        <!-- Sidebar Message -->
        <!-- <div class="sidebar-card">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div> -->

    </ul>
@endsection
