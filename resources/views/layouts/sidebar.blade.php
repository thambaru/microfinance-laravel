<?php

use App\Libraries\Common;

?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-text mx-3"><img src="{{asset('img/cheetah-logo.jpg')}}"></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Nav Item - Pages Collapse Menu -->

    <li class="nav-item @if(Common::isRoute('customers')) active @endif">
        <a class="nav-link @if(!Common::isRoute('customers')) collapsed @endif" href="#" data-toggle="collapse" data-target="#collapseCustomers" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-user"></i>
            <span>Customers</span>
        </a>
        <div id="collapseCustomers" class="collapse @if(Common::isRoute('customers')) show @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{route('customers.index')}}">List All</a>
                <a class="collapse-item" href="{{route('customers.create')}}">Create</a>
            </div>
        </div>
    </li>

</ul>
<!-- End of Sidebar -->