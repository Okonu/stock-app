 
 <div class="dlabnav">
            <div class="dlabnav-scroll">
				<ul class="metismenu" id="menu">
                    
					<li>
						<a class="ai-icon"href="{{ route('dashboard') }}">
						<i class="flaticon-381-television"></i>
						<span class="nav-text">Dashboard</span>
						</a>
					</li>
					<li>
						<a class="ai-icon"href="{{  route('jobs.create.view') }}">
						<i class="flaticon-381-network"></i>
						<span class="nav-text">Create Jobs</span>
						</a>
					</li>
					<li>
						<a class="ai-icon"href="{{ route('jobs.show') }}">
						<i class="flaticon-381-layer-1"></i>
						<span class="nav-text">Manage Jobs</span>
						</a>
					</li>
					<li>
						<a class="ai-icon"href="{{ route('jobs.applications') }}">
						<i class="flaticon-381-layer-1"></i>
						<span class="nav-text">Job Applications</span>
						</a>
					</li>

					<li>
						<a class="ai-icon"href="{{ route('profile') }}">
						<i class="flaticon-381-user"></i>
						<span class="nav-text">Company Profile</span>
						</a>
					</li>
					
				
                </ul>
				<a class="add-menu-sidebar"   href="{{ route('logout') }}">Logout</a>
				
			</div>
        </div>