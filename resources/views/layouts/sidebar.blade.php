<aside class="main-sidebar">

    <!-- sidebar-->
    <section class="sidebar">

        <!-- Sidebar user  -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('user-profile.png') }} " class="img-circle" alt="User Image">
            </div>
        
        </div>
        <!-- search form -->
        
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <!-- <li class="header">Functions</li> -->
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="{{ route('stocks.index') }}"><i class="fa fa-list"></i> <span>Physical Stock</span></a></li>
            <li><a href="{{ route('legacies.index') }}"><i class="fa fa-cubes"></i> <span>System Stock</span></a></li>
            <li><a href="{{ route('reconciliation.index') }}"><i class="fa fa-list"></i> <span>Recocniliation</span></a></li>
            <li><a href="{{ route('warehouses.index') }}"><i class="fa fa-list"></i> <span>Warehouse</span></a></li>
            <li><a href="{{ route('owners.index') }}"><i class="fa fa-users"></i> <span>Producers</span></a></li>
            <li><a href="{{ route('gardens.index') }}"><i class="fa fa-list"></i> <span>Gardens</span></a></li>
            <li><a href="{{ route('grades.index') }}"><i class="fa fa-list"></i> <span>Tea Grades</span></a></li>
            <li><a href="{{ route('packages.index') }}"><i class="fa fa-cubes"></i> <span>Package Types</span></a></li>
            <li><a href="{{ route('user.index') }}"><i class="fa fa-user-secret"></i> <span>System Users</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
