@include('layout')
@include('header')
@include('sidebar')

<div class="page-content-wrapper">
				<div class="page-content">
					<div class="page-bar">
						<div class="page-title-breadcrumb">
							<div class=" pull-left">
								<div class="page-title">Bays</div>
							</div>
							<ol class="breadcrumb page-breadcrumb pull-right">
								
								<li><a class="parent-item" href="#">Bays</a>&nbsp;<i class="fa fa-angle-right"></i>
								</li>
								<li class="active">Bays List</li>
							</ol>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="tabbable-line">
								<div class="tab-content">
									<div class="tab-pane active fontawesome-demo" id="tab1">
										<div class="row">
											<div class="col-md-12">
												<div class="card card-box">
													<div class="card-head">
														<header>Bays List</header>
														<div class="tools">
															<a class="fa fa-repeat btn-color box-refresh"
																href="javascript:;"></a>
															<a class="t-collapse btn-color fa fa-chevron-down"
																href="javascript:;"></a>
															<a class="t-close btn-color fa fa-times"
																href="javascript:;"></a>
														</div>
													</div>
													<div class="card-body ">
														<div class="row">
															<div class="col-md-6 col-sm-6 col-6">
																<div class="btn-group">
																	<a href="add_professor.html" id="addRow"
																		class="btn btn-primary">
																		Add New <i class="fa fa-plus"></i>
																	</a>
																</div>
															</div>
														</div>
														<table
															class="table table-striped table-bordered table-hover table-checkable order-column valign-middle"
															id="example4">
															<thead>
																<tr>
																	<th></th>
																	<th>Warehouse</th>
																	<th> Bays </th>
																	<th> Action </th>
																</tr>
															</thead>
															<tbody>
																<tr class="odd gradeX">
																	<td class="patient-img">
																		<img src="../assets/img/user/user1.jpg" alt="">
																	</td>
																	<td class="left"></td>
																	<td></td>
																	<td class="left"></td>
																	<td><a href="tel:4444565756">
																			 </a></td>
																	<td><a href="mailto:">
																			</a></td>
																	<td class="left"></td>
																	<td>
																		<a href="edit_student.html" class="tblEditBtn">
																			<i class="fa fa-pencil"></i>
																		</a>
																		<a class="tblDelBtn">
																			<i class="fa fa-trash-o"></i>
																		</a>
																	</td>
																</tr>
																
																
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>