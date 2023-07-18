 
 <div class="dlabnav">
            <div class="dlabnav-scroll">
				<ul class="metismenu" id="menu">
                    
					<li>
						<a class="ai-icon"href="{{ route('admin.dashboard') }}">
						<i class="flaticon-381-television"></i>
						<span class="nav-text">Dashboard</span>
						</a>
					</li>
					<li>
						<a class="ai-icon"href="{{route('admin.jobs.create.view') }}">
						<i class="flaticon-381-network"></i>
						<span class="nav-text">Create Jobs</span>
						</a>
					</li>
					<li>
						<a class="ai-icon"href="{{ route('admin.jobs.show') }}">
						<i class="flaticon-381-layer-1"></i>
						<span class="nav-text">Manage Jobs</span>
						</a>
					</li>
					<li>
						<a class="ai-icon"href="{{ route('admin.jobs.applications') }}">
						<i class="flaticon-381-layer-1"></i>
						<span class="nav-text">Job Applications</span>
						</a>
					</li>

					<li>
						<a class="ai-icon"href="{{ route('admin.profile') }}">
						<i class="flaticon-381-user"></i>
						<span class="nav-text">Company Profile</span>
						</a>
					</li>
					<hr>
					<li>
						<a class="ai-icon"href="">
						
						<span class="nav-text">Admin Section</span>
						</a>
					</li>
					<hr>
					<li>
						<a class="ai-icon"href="{{ route('manage.application') }}">
						<i class="flaticon-381-user"></i>
						<span class="nav-text">Applications</span>
						</a>
					</li>
					<li>
						<a class="ai-icon"href="{{ route('report.report') }}">
						<i class="flaticon-381-user"></i>
						<span class="nav-text">Report</span>
						</a>
					</li>
					<li>
						<a class="ai-icon"href="{{ route('communicate.message') }}">
						<i class="flaticon-381-user"></i>
						<span class="nav-text">Communication</span>
						</a>
					</li>

					  <li><a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
							<i class="flaticon-381-networking"></i>
							<span class="nav-text">Manage Settings</span>
						</a>
                        <ul aria-expanded="false">
							<li><a href="{{ route('admin.settings.location') }}">Locations</a></li>
							<li><a href="{{ route('admin.settings.companytype') }}">Partner Type</a></li>
							<li><a href="{{ route('admin.settings.skills') }}">Skills</a></li>
							<li><a href="{{ route('admin.settings.level') }}">Highest Level</a></li>
							<li><a href="{{ route('admin.settings.interest') }}">Interest</a></li>
							
						</ul>
                    </li>
					
				
                </ul>
				<a class="add-menu-sidebar"   href="{{ route('logout') }}">Logout</a>
				
			</div>
        </div>