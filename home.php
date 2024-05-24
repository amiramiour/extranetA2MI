<!DOCTYPE html>
<html lang="fr" >
<head>
	<meta charset="utf-8" />
	<title>A2MI | Accueil</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/transparent/app.min.css" rel="stylesheet" />
	<link href="assets/css/custom.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
	
	<!-- ================== BEGIN page-css ================== -->
	<link href="assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
	
	<!-- CSS pour notification -->
	<!--<link href="../assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />-->
	<!-- ================== END page-css ================== -->
</head>
<?php 
session_start();
require_once('includes/_functions.php');
require_once('connexion/traitement_connexion.php');
if (isset($_SESSION['role']) AND $_SESSION['role'] == 'admin' AND isset($_SESSION['mail'])){ ?>
<body>
	<!-- BEGIN page-cover -->
	<div class="app-cover"></div>
	<!-- END page-cover -->
	
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader">
		<span class="spinner"></span>
	</div>
	<!-- END #loader -->

	<!-- BEGIN #app -->
	<div id="app" class="app app-header-fixed app-sidebar-fixed app-content-full-height">

        <?php require_once('includes/header.php'); ?>
        <?php require_once('includes/sidebar.php'); ?>
		
		<!-- BEGIN #content -->
		<div id="content" class="app-content">
			<!-- BEGIN breadcrumb -->
			<ol class="breadcrumb float-xl-end">
				<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
				<li class="breadcrumb-item active">Dashboard</li>
			</ol>
			<!-- END breadcrumb -->
			<!-- BEGIN page-header -->
			<h1 class="page-header">Bienvenue <small><?= $_SESSION['firstname'] ?> !</small></h1>
			<!-- END page-header -->
			
            <!-- BEGIN row -->
			<div class="row">
				<!-- BEGIN col-3 -->
				<div class="col-xl-2 col-sm-6">
					<div class="widget widget-stats bg-gradient-lime-dgray">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-globe fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">TODAY'S VISITS</div>
							<div class="stats-number">7,842,900</div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: 70.1%;"></div>
							</div>
							<div class="stats-desc">Better than last week (70.1%)</div>
						</div>
						<div class="stats-link">
							<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- END col-3 -->
				<!-- BEGIN col-3 -->
				<div class="col-xl-2 col-sm-6">
					<div class="widget widget-stats bg-gradient-cyan-dgray">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-dollar-sign fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">TODAY'S PROFIT</div>
							<div class="stats-number">180,200</div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: 40.5%;"></div>
							</div>
							<div class="stats-desc">Better than last week (40.5%)</div>
						</div>
						<div class="stats-link">
							<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- END col-3 -->
				<!-- BEGIN col-3 -->
				<div class="col-xl-2 col-sm-6">
					<div class="widget widget-stats bg-gradient-indigo-dgray">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-archive fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">NEW ORDERS</div>
							<div class="stats-number">38,900</div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: 76.3%;"></div>
							</div>
							<div class="stats-desc">Better than last week (76.3%)</div>
						</div>
						<div class="stats-link">
							<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- END col-3 -->
				<!-- BEGIN col-3 -->
				<div class="col-xl-2 col-sm-6">
					<div class="widget widget-stats bg-gradient-pink-dgray">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-comment-alt fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">NEW COMMENTS</div>
							<div class="stats-number">3,988</div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: 54.9%;"></div>
							</div>
							<div class="stats-desc">Better than last week (54.9%)</div>
						</div>
						<div class="stats-link">
							<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- END col-3 -->		
				<!-- BEGIN col-3 -->
				<div class="col-xl-2 col-sm-6">
					<div class="widget widget-stats bg-gradient-red-dgray">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-desktop fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">TODAY'S VISITS</div>
							<div class="stats-number">7,842,900</div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: 70.1%;"></div>
							</div>
							<div class="stats-desc">Better than last week (70.1%)</div>
						</div>
						<div class="stats-link">
							<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- END col-3 -->				
				<!-- BEGIN col-3 -->
				<div class="col-xl-2 col-sm-6">
					<div class="widget widget-stats bg-gradient-orange-dgray">
						<div class="stats-icon stats-icon-lg"><i class="fa fa-users fa-fw"></i></div>
						<div class="stats-content">
							<div class="stats-title">NEW ORDERS</div>
							<div class="stats-number">38,900</div>
							<div class="stats-progress progress">
								<div class="progress-bar" style="width: 76.3%;"></div>
							</div>
							<div class="stats-desc">Better than last week (76.3%)</div>
						</div>
						<div class="stats-link">
							<a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
						</div>
					</div>
				</div>
				<!-- END col-3 -->				
			</div>
			<!-- END row -->
									
			<!-- BEGIN row -->
			<div class="row">
				<!-- BEGIN col-8 -->
				<div class="col-xl-8">					
					<!-- BEGIN tabs -->
					<ul class="nav nav-tabs nav-tabs-inverse nav-justified" data-sortable-id="index-2">
						<li class="nav-item"><a href="#latest-post" data-bs-toggle="tab" class="nav-link active"><i class="fa fa-camera fa-lg me-5px"></i> <span class="d-none d-md-inline">Latest Post</span></a></li>
						<li class="nav-item"><a href="#purchase" data-bs-toggle="tab" class="nav-link"><i class="fa fa-archive fa-lg me-5px"></i> <span class="d-none d-md-inline">Purchase</span></a></li>
						<li class="nav-item"><a href="#email" data-bs-toggle="tab" class="nav-link"><i class="fa fa-envelope fa-lg me-5px"></i> <span class="d-none d-md-inline">Email</span></a></li>
					</ul>
					<div class="tab-content panel rounded-0 rounded-bottom mb-20px" data-sortable-id="index-3">
						<div class="tab-pane fade active show" id="latest-post">
							<div class="h-500px p-3" data-scrollbar="true">
								<div class="d-sm-flex">
									<a href="javascript:;" class="w-sm-200px">
										<img class="mw-100 rounded" src="assets/img/gallery/gallery-1.jpg" alt="" />
									</a>
									<div class="flex-1 ps-sm-3 pt-3 pt-sm-0">
										<h5 class="text-white">Aenean viverra arcu nec pellentesque ultrices. In erat purus, adipiscing nec lacinia at, ornare ac eros.</h5>
										Nullam at risus metus. Quisque nisl purus, pulvinar ut mauris vel, elementum suscipit eros. Praesent ornare ante massa, egestas pellentesque orci convallis ut. Curabitur consequat convallis est, id luctus mauris lacinia vel. Nullam tristique lobortis mauris, ultricies fermentum lacus bibendum id. Proin non ante tortor. Suspendisse pulvinar ornare tellus nec pulvinar. Nam pellentesque accumsan mi, non pellentesque sem convallis sed. Quisque rutrum erat id auctor gravida.
									</div>
								</div>
								<hr class="bg-gray-500" />
								<div class="d-sm-flex">
									<a href="javascript:;" class="w-sm-200px">
										<img class="mw-100 rounded" src="assets/img/gallery/gallery-10.jpg" alt="" />
									</a>
									<div class="flex-1 ps-sm-3 pt-3 pt-sm-0">
										<h5 class="text-white">Vestibulum vitae diam nec odio dapibus placerat. Ut ut lorem justo.</h5>
										Fusce bibendum augue nec fermentum tempus. Sed laoreet dictum tempus. Aenean ac sem quis nulla malesuada volutpat. Nunc vitae urna pulvinar velit commodo cursus. Nullam eu felis quis diam adipiscing hendrerit vel ac turpis. Nam mattis fringilla euismod. Donec eu ipsum sit amet mauris iaculis aliquet. Quisque sit amet feugiat odio. Cras convallis lorem at libero lobortis, placerat lobortis sapien lacinia. Duis sit amet elit bibendum sapien dignissim bibendum.
									</div>
								</div>
								<hr class="bg-gray-500" />
								<div class="d-sm-flex">
									<a href="javascript:;" class="w-sm-200px">
										<img class="mw-100 rounded" src="assets/img/gallery/gallery-7.jpg" alt="" />
									</a>
									<div class="flex-1 ps-sm-3 pt-3 pt-sm-0">
										<h5 class="text-white">Maecenas eget turpis luctus, scelerisque arcu id, iaculis urna. Interdum et malesuada fames ac ante ipsum primis in faucibus.</h5>
										Morbi placerat est nec pharetra placerat. Ut laoreet nunc accumsan orci aliquam accumsan. Maecenas volutpat dolor vitae sapien ultricies fringilla. Suspendisse vitae orci sed nibh ultrices tristique. Aenean in ante eget urna semper imperdiet. Pellentesque sagittis a nulla at scelerisque. Nam augue nulla, accumsan quis nisi a, facilisis eleifend nulla. Praesent aliquet odio non imperdiet fringilla. Morbi a porta nunc. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.
									</div>
								</div>
								<hr class="bg-gray-500" />
								<div class="d-sm-flex">
									<a href="javascript:;" class="w-sm-200px">
										<img class="mw-100 rounded" src="assets/img/gallery/gallery-8.jpg" alt="" />
									</a>
									<div class="flex-1 ps-sm-3 pt-3 pt-sm-0">
										<h5 class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec auctor accumsan rutrum.</h5>
										Fusce augue diam, vestibulum a mattis sit amet, vehicula eu ipsum. Vestibulum eu mi nec purus tempor consequat. Vestibulum porta non mi quis cursus. Fusce vulputate cursus magna, tincidunt sodales ipsum lobortis tincidunt. Mauris quis lorem ligula. Morbi placerat est nec pharetra placerat. Ut laoreet nunc accumsan orci aliquam accumsan. Maecenas volutpat dolor vitae sapien ultricies fringilla. Suspendisse vitae orci sed nibh ultrices tristique. Aenean in ante eget urna semper imperdiet. Pellentesque sagittis a nulla at scelerisque.
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="purchase">
							<div class="h-500px" data-scrollbar="true">
								<table class="table table-panel mb-0">
									<thead>
										<tr>
											<th>Date</th>
											<th class="hidden-sm text-center">Product</th>
											<th></th>
											<th>Amount</th>
											<th>User</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="fw-bold text-muted">13/02/2021</td>
											<td class="hidden-sm text-center">
												<a href="javascript:;">
													<img src="assets/img/product/product-1.png" alt="" width="32px"  />
												</a>
											</td>
											<td class="text-nowrap">
												<h6><a href="javascript:;" class="text-white text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
											</td>
											<td class="text-blue fw-bold">$349.00</td>
											<td class="text-nowrap"><a href="javascript:;" class="text-white text-decoration-none">Derick Wong</a></td>
										</tr>
										<tr>
											<td class="fw-bold text-muted">13/02/2021</td>
											<td class="hidden-sm text-center">
												<a href="javascript:;">
													<img src="assets/img/product/product-2.png" alt="" width="32px" />
												</a>
											</td>
											<td class="text-nowrap">
												<h6><a href="javascript:;" class="text-white text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
											</td>
											<td class="text-blue fw-bold">$399.00</td>
											<td class="text-nowrap"><a href="javascript:;" class="text-white text-decoration-none">Derick Wong</a></td>
										</tr>
										<tr>
											<td class="fw-bold text-muted">13/02/2021</td>
											<td class="hidden-sm text-center">
												<a href="javascript:;">
													<img src="assets/img/product/product-3.png" alt="" width="32px" />
												</a>
											</td>
											<td class="text-nowrap">
												<h6><a href="javascript:;" class="text-white text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
											</td>
											<td class="text-blue fw-bold">$499.00</td>
											<td class="text-nowrap"><a href="javascript:;" class="text-white text-decoration-none">Derick Wong</a></td>
										</tr>
										<tr>
											<td class="fw-bold text-muted">13/02/2021</td>
											<td class="hidden-sm text-center">
												<a href="javascript:;">
													<img src="assets/img/product/product-4.png" alt="" width="32px" />
												</a>
											</td>
											<td class="text-nowrap">
												<h6><a href="javascript:;" class="text-white text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
											</td>
											<td class="text-blue fw-bold">$230.00</td>
											<td class="text-nowrap"><a href="javascript:;" class="text-white text-decoration-none">Derick Wong</a></td>
										</tr>
										<tr>
											<td class="fw-bold text-muted">13/02/2021</td>
											<td class="hidden-sm text-center">
												<a href="javascript:;">
													<img src="assets/img/product/product-5.png" alt="" width="32px" />
												</a>
											</td>
											<td class="text-nowrap">
												<h6><a href="javascript:;" class="text-white text-decoration-none">Nunc eleifend lorem eu velit eleifend, <br />eget faucibus nibh placerat.</a></h6>
											</td>
											<td class="text-blue fw-bold">$500.00</td>
											<td class="text-nowrap"><a href="javascript:;" class="text-white text-decoration-none">Derick Wong</a></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="email">
							<div class="h-500px p-3" data-scrollbar="true">
								<div class="d-flex">
									<a class="w-60px" href="javascript:;">
										<img src="assets/img/user/user-1.jpg" alt="" class="mw-100 rounded-pill" />
									</a>
									<div class="flex-1 ps-3">
										<a href="javascript:;" class="text-white text-decoration-none"><h5>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h5></a>
										<p class="mb-5px">
											Aenean mollis arcu sed turpis accumsan dignissim. Etiam vel tortor at risus tristique convallis. Donec adipiscing euismod arcu id euismod. Suspendisse potenti. Aliquam lacinia sapien ac urna placerat, eu interdum mauris viverra.
										</p>
										<span class="text-muted fs-11px fw-bold">Received on 04/16/2021, 12.39pm</span>
									</div>
								</div>
								<hr class="bg-gray-500" />
								<div class="d-flex">
									<a class="w-60px" href="javascript:;">
										<img src="assets/img/user/user-2.jpg" alt="" class="mw-100 rounded-pill" />
									</a>
									<div class="flex-1 ps-3">
										<a href="javascript:;" class="text-white text-decoration-none"><h5>Praesent et sem porta leo tempus tincidunt eleifend et arcu.</h5></a>
										<p class="mb-5px">
											Proin adipiscing dui nulla. Duis pharetra vel sem ac adipiscing. Vestibulum ut porta leo. Pellentesque orci neque, tempor ornare purus nec, fringilla venenatis elit. Duis at est non nisl dapibus lacinia.
										</p>
										<span class="text-muted fs-11px fw-bold">Received on 04/16/2021, 12.39pm</span>
									</div>
								</div>
								<hr class="bg-gray-500" />
								<div class="d-flex">
									<a class="w-60px" href="javascript:;">
										<img src="assets/img/user/user-3.jpg" alt="" class="mw-100 rounded-pill" />
									</a>
									<div class="flex-1 ps-3">
										<a href="javascript:;" class="text-white text-decoration-none"><h5>Ut mi eros, varius nec mi vel, consectetur convallis diam.</h5></a>
										<p class="mb-5px">
											Ut mi eros, varius nec mi vel, consectetur convallis diam. Nullam eget hendrerit eros. Duis lacinia condimentum justo at ultrices. Phasellus sapien arcu, fringilla eu pulvinar id, mattis quis mauris.
										</p>
										<span class="text-muted fs-11px fw-bold">Received on 04/16/2021, 12.39pm</span>
									</div>
								</div>
								<hr class="bg-gray-500" />
								<div class="d-flex">
									<a class="w-60px" href="javascript:;">
										<img src="assets/img/user/user-4.jpg" alt="" class="mw-100 rounded-pill" />
									</a>
									<div class="flex-1 ps-3">
										<a href="javascript:;" class="text-white text-decoration-none"><h5>Aliquam nec dolor vel nisl dictum ullamcorper.</h5></a>
										<p class="mb-5px">
											Aliquam nec dolor vel nisl dictum ullamcorper. Duis vel magna enim. Aenean volutpat a dui vitae pulvinar. Nullam ligula mauris, dictum eu ullamcorper quis, lacinia nec mauris.
										</p>
										<span class="text-muted fs-11px fw-bold">Received on 04/16/2021, 12.39pm</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END tabs -->	
				</div>
				<!-- END col-8 -->
				<!-- BEGIN col-4 -->
				<div class="col-xl-4">
															
					<!-- BEGIN panel -->
					<div class="panel panel-inverse" data-sortable-id="index-8">
						<div class="panel-heading">
							<h4 class="panel-title">Todo List</h4>
							<div class="panel-heading-btn">
								<a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
								<a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
							</div>
						</div>
						<div class="panel-body p-0">
							<div class="todolist">
								<div class="todolist-item active">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist1" data-change="todolist" checked />
										</div>
									</div>
									<label class="todolist-label" for="todolist1">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist2" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist2">Duis a ullamcorper massa.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist3" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist3">Phasellus bibendum, odio nec vestibulum ullamcorper.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist4" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist4">Duis pharetra mi sit amet dictum congue.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist5" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum congue.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist6" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec vestibulum ullamcorper.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist7" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist5" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum congue.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist6" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec vestibulum ullamcorper.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist7" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist5" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum congue.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist6" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec vestibulum ullamcorper.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist7" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist5" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist5">Duis pharetra mi sit amet dictum congue.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist6" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist6">Phasellus bibendum, odio nec vestibulum ullamcorper.</label>
								</div>
								<div class="todolist-item">
									<div class="todolist-input">
										<div class="form-check">
											<input type="checkbox" class="form-check-input" id="todolist7" data-change="todolist" />
										</div>
									</div>
									<label class="todolist-label" for="todolist7">Donec vehicula pretium nisl, id lacinia nisl tincidunt id.</label>
								</div>
							</div>
						</div>
					</div>
					<!-- END panel -->
					

				</div>
				<!-- END col-4 -->
			</div>
			<!-- END row -->
            <?php require_once('includes/footer.php'); ?>
		</div>
		<!-- END #content -->
	
		<!-- BEGIN scroll-top-btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
		<!-- END scroll-top-btn -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="assets/js/vendor.min.js"></script>
	<script src="assets/js/app.min.js"></script>
	<!-- ================== END core-js ================== -->
		
	<!-- JS pour notification -->
	<!--<script src="../assets/plugins/gritter/js/jquery.gritter.js"></script>-->
	
	<!-- ================== BEGIN page-js ================== -->
	<script src="assets/plugins/flot/source/jquery.canvaswrapper.js"></script>
	<script src="assets/plugins/flot/source/jquery.colorhelpers.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.saturated.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.browser.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.drawSeries.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.uiConstants.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.time.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.resize.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.pie.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.crosshair.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.categories.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.navigate.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.touchNavigate.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.hover.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.touch.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.selection.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.symbol.js"></script>
	<script src="assets/plugins/flot/source/jquery.flot.legend.js"></script>
	<script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
	<script src="assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
	<script src="assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
	<script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/demo/dashboard.js"></script>
	<!-- ================== END page-js ================== -->
</body>
<?php } else { ?>	
<?php require_once('login.php'); ?>
<?php } ?>
</html>