@extends ('layout')

@section('container')
<div class="page-container">
			
			<!-- start page content -->
			<div class="page-content-wrapper">
				<div class="page-content">
					<div class="page-bar">
						<div class="page-title-breadcrumb">
							<div class=" pull-left">
								<div class="page-title">Dashboard</div>
							</div>
							<ol class="breadcrumb page-breadcrumb pull-right">
								<li><i class="fa fa-home"></i>&nbsp;<a class="parent-item"
										href="index-2.html">Home</a>&nbsp;<i class="fa fa-angle-right"></i>
								</li>
								<li class="active">Dashboard</li>
							</ol>
						</div>
					</div>
					<!-- start widget -->
					<div class="state-overview">
						<div class="row">
							<div class="col-xl-3 col-md-6 col-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col mt-0">
												<h4 class="info-box-title  text-dark">GARDENS</h4>
											</div>
											<div class="col-auto">
												<div class="l-bg-green info-icon">
													<i class="fa fa-building pull-left col-gray font-30"></i>
												</div>
											</div>
										</div>
										<h1 class="mt-1 mb-3 info-box-title text-muted">08</h1>										
									</div>
								</div>
							</div>
							<!-- /.col --> 

							<div class="col-xl-3 col-md-6 col-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col mt-0">
												<h4 class="info-box-title  text-dark">TOTAL PKGS</h4>
											</div>
											<div class="col-auto">
												<div class="l-bg-green info-icon">
													<i class="fa fa-building pull-left col-gray font-30"></i>
												</div>
											</div>
										</div>
										<h1 class="mt-1 mb-3 info-box-title text-muted">21</h1>										
									</div>
								</div>
							</div>
							<!-- /.col --> 

							<div class="col-xl-3 col-md-6 col-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col mt-0">
												<h4 class="info-box-title  text-dark">INVOICES </h4>
											</div>
											<div class="col-auto">
												<div class="l-bg-green info-icon">
													<i class="fas fa-calendar-o pull-left col-blue font-30"></i>
												</div>
											</div>
										</div>
										<h1 class="mt-1 mb-3 info-box-title text-muted">04</h1>										
									</div>
								</div>
							</div>
							<!-- /.col --> 

							<div class="col-xl-3 col-md-6 col-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col mt-0">
												<h4 class="info-box-title  text-dark">CLERKS</h4>
											</div>
											<div class="col-auto">
												<div class="l-bg-green info-icon">
													<i class="fa fa-users pull-left col-blue font-30"></i>
												</div>
											</div>
										</div>
										<h1 class="mt-1 mb-3 info-box-title text-muted">03</h1>										
									</div>
								</div>
							</div>
							<!-- /.col --> 

						</div>
					</div>
					<!-- end widget -->

					<!-- start new trour request list -->
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="card  card-box">
								<div class="card-head text-uppercase">
									<header><i class="fas fa-calendar-o pull-left col-blue font-30"></i> &nbsp;&nbsp; STOCK TAKEN</header>
									<div class="tools">
										<a class="fa fa-repeat btn-color box-refresh" href="javascript:;"></a>
										<a class="t-collapse btn-color fa fa-chevron-down" href="javascript:;"></a>
										<a class="t-close btn-color fa fa-times" href="javascript:;"></a>
									</div>
								</div>
								<div class="card-body ">
									<div class="table-wrap">
										<div class="table-responsive">
											<table class="table display product-overview mb-30" id="support_table">
												<thead>
													<tr>
														<th>#</th>
														<th>Warehouse</th>
														<!-- <th>Owner</th> -->
														<th>Bays</th>
														<th>Garden</th>
														<th>Invoice</th>
														<th>Grade</th>
														<th>PKGS</th>
														<th>PKG Type</th>
														<th>Date</th>
														<th>Remarks</th>
														<th>Action/Print</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td>Jens Brincker</td>
														<td>Kenny Josh</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>27/05/2016</td>
														<!-- <td>
															<span class="label label-sm label-success">completed</span>
														</td> -->
														<td>Mechanical</td>
														<td>
															<a href="javascript:void(0)" class="tblEditBtn">
																<i class="fa fa-pencil"></i>
															</a>
															<a href="javascript:void(0)" class="tblDelBtn">
																<i class="fa fa-trash-o"></i>
															</a>
														</td>
													</tr>
													<tr>
														<td>2</td>
														<td>Mark Hay</td>
														<td> Mark</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>26/05/2017</td>
														<!-- <td>
															<span class="label label-sm label-warning">pending
															</span>
														</td> -->
														<td>Science</td>
														<td>
															<a href="javascript:void(0)" class="tblEditBtn">
																<i class="fa fa-pencil"></i>
															</a>
															<a href="javascript:void(0)" class="tblDelBtn">
																<i class="fa fa-trash-o"></i>
															</a>
														</td>
													</tr>
													<tr>
														<td>3</td>
														<td>Anthony Davie</td>
														<td>Cinnabar</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>Sue Woodger</td>
														<td>21/05/2016</td>
														<!-- <td>
															<span class="label label-sm label-success ">completed</span>
														</td> -->
														<td>Commerce</td>
														<td>
															<a href="javascript:void(0)" class="tblEditBtn">
																<i class="fa fa-pencil"></i>
															</a>
															<a href="javascript:void(0)" class="tblDelBtn">
																<i class="fa fa-trash-o"></i>
															</a>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- end new trour request list -->
				</div>
			</div>
			<!-- end page content -->
		</div>
@endsection