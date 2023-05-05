@include('layout')
@include('header')
@include('sidebar')
<!-- start page content -->
<div class="page-content-wrapper">
				<div class="page-content">
					<div class="page-bar">
						<div class="page-title-breadcrumb">
							<div class=" pull-left">
								<div class="page-title">Add Grade</div>
							</div>
							<ol class="breadcrumb page-breadcrumb pull-right">
								<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
										href="#">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
								</li>
								<li><a class="parent-item" href="{{url('/display/grade')}}">Tea Grade</a>&nbsp;<i class="fa fa-angle-right"></i>
								</li>
								<li class="active">Add tea Grade</li>
							</ol>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="card-box">
								<div class="card-head">
									<header>Add Tea Grade</header>
									
								</div>
								<div class="card-body row">
                                    <div class="col-lg-6 p-t-20">
										<div
											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
											<input class="mdl-textfield__input" type="text" id="list6" value="" readonly
												tabIndex="-1">
											<label for="list2" class="pull-right margin-0">
												<i class="mdl-icon-toggle__label material-icons"></i>
											</label>
											<label for="list2" class="mdl-textfield__label">Grade</label>
											<ul data-mdl-for="list6" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
												<li class="mdl-menu__item" data-val="DE">PF-1</li>
												<li class="mdl-menu__item" data-val="BY">BP</li>
												<li class="mdl-menu__item" data-val="BY">GR 2</li>
                                                <li class="mdl-menu__item" data-val="BY">GR 1</li>
                                                <li class="mdl-menu__item" data-val="BY">TA 9</li>
											</ul>
										</div>
									</div>
									<div class="col-lg-12 p-t-20 text-center">
										<button type="button"
											class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 m-r-20 btn-circle btn-primary">Submit</button>
										<button type="button"
											class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect m-b-10 btn-circle btn-danger">Cancel</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end page content -->