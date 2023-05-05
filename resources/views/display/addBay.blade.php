@include('layout')
@include('header')
@include('sidebar')
<!-- start page content -->
<div class="page-content-wrapper">
				<div class="page-content">
					<div class="page-bar">
						<div class="page-title-breadcrumb">
							<div class=" pull-left">
								<div class="page-title">Add Bays/Aisles</div>
							</div>
							<ol class="breadcrumb page-breadcrumb pull-right">
								<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
										href="index-2.html">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
								</li>
								<li><a class="parent-item" href="#">Bays/Aisles</a>&nbsp;<i class="fa fa-angle-right"></i>
								</li>
								<li class="active">Add Bays/Aisles</li>
							</ol>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="card-box">
								<div class="card-head">
									<header>Add Bays/Aisles</header>
									
								</div>
								<div class="card-body row">
                                    <div class="col-lg-6 p-t-20">
										<div
											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
											<input class="mdl-textfield__input" type="text" id="list8" value="" readonly
												tabIndex="-1">
											<label for="list2" class="pull-right margin-0">
												<i class="mdl-icon-toggle__label material-icons"></i>
											</label>
											<label for="list2" class="mdl-textfield__label">Warehouse</label>
											<ul data-mdl-for="list8" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
												<li class="mdl-menu__item" data-val="DE">ANNEX 3</li>
												<li class="mdl-menu__item" data-val="BY">UPSTAIRS(main transit)</li>
												<li class="mdl-menu__item" data-val="BY">TRANSIT A(main transit)</li>
                                                <li class="mdl-menu__item" data-val="BY">TRANSIT B(main transit)</li>
                                                <li class="mdl-menu__item" data-val="BY">LOCAL</li>
											</ul>
										</div>
									</div>
									<div class="col-lg-6 p-t-20">
										<div
											class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label getmdl-select getmdl-select__fix-height txt-full-width">
											<input class="mdl-textfield__input" type="text" id="list2" value="" readonly
												tabIndex="-1">
											<label for="list2" class="pull-right margin-0">
												<i class="mdl-icon-toggle__label material-icons"></i>
											</label>
											<label for="list2" class="mdl-textfield__label">Bay/Aisle</label>
											<ul data-mdl-for="list2" class="mdl-menu mdl-menu--bottom-left mdl-js-menu">
												<li class="mdl-menu__item" data-val="DE">A1</li>
												<li class="mdl-menu__item" data-val="BY">A2</li>
												<li class="mdl-menu__item" data-val="BY">A3</li>
                                                <li class="mdl-menu__item" data-val="BY">B1</li>
                                                <li class="mdl-menu__item" data-val="BY">B2</li>
                                                <li class="mdl-menu__item" data-val="BY">B3</li>
                                                <li class="mdl-menu__item" data-val="BY">C1</li>
                                                <li class="mdl-menu__item" data-val="BY">C2</li>
                                                <li class="mdl-menu__item" data-val="BY">C3</li>
											</ul>
										</div>
									</div>
									<div class="col-lg-12 p-t-20">
										<div class="mdl-textfield mdl-js-textfield txt-full-width">
											<textarea class="mdl-textfield__input" rows="4" id="text7"></textarea>
											<label class="mdl-textfield__label" for="text7">Remark</label>
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