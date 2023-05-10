<aside class="main-sidebar">

    <!-- sidebar -->
    <section class="sidebar">

        <!-- Sidebar user panel-->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('user-profile.png') }} " class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ \Auth::user()->name  }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
       
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <!-- <li class="header">Functions</li> -->
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href=""><i class="fa fa-list"></i> <span>Stock Taken</span></a></li>
            <li><a href="{{ route('grades.index') }}"><i class="fa fa-list"></i> <span>Tea Grade</span></a></li>
            <li><a href="{{ route('customers.index') }}"><i class="fa fa-users"></i> <span>Farm Owners</span></a></li>
            <li><a href="{{ route('packages.index') }}"><i class="fa fa-list"></i> <span>Package Type</span></a></li>
            <li><a href="{{ route('warehouses.index') }}"><i class="fa fa-list"></i> <span>Warehouse</span></a></li>
            <li><a href="{{ route('bays.index') }}"><i class="fa fa-cubes"></i> <span>Bays</span></a></li>
            <li><a href="{{ route('categories.index') }}"><i class="fa fa-users"></i> <span>Categories</span></a></li>
            <li><a href="{{ route('user.index') }}"><i class="fa fa-user-secret"></i> <span>System Users</span></a></li>

        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
