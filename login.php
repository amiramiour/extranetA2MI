<!DOCTYPE html>
<html lang="fr" >
<head>
	<meta charset="utf-8" />
	<title>A2MI | Login</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/transparent/app.min.css" rel="stylesheet" />
	<link href="assets/css/custom.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
</head>
<body class='pace-top'>
	<!-- BEGIN page-cover -->
	<div class="app-cover"></div>
	<!-- END page-cover -->
	
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader">
		<span class="spinner"></span>
	</div>
	<!-- END #loader -->


	<!-- BEGIN #app -->
	<div id="app" class="app">
		<!-- BEGIN login -->
		<div class="login login-v1">
			<!-- BEGIN login-container -->
			<div class="login-container">
				<!-- BEGIN login-header -->
				<div class="login-header">
					<div class="brand">
						<div class="d-flex align-items-center">
							<span class="logo-a2mi50"></span> <b>A2MI</b>-Informatique
						</div>
						<small></small>
					</div>
					<div class="icon">
						<i class="fa fa-lock"></i>
					</div>
				</div>
				<!-- END login-header -->
				
				<!-- BEGIN login-body -->
				<div class="login-body">
					<!-- BEGIN login-content -->
					<div class="login-content fs-13px">
						<form action="index.php" method="POST">
							<div class="form-floating mb-20px">
								<input type="email" class="form-control fs-13px h-45px" id="emailAddress" name="mail" placeholder="Email" required />
								<label for="emailAddress" class="d-flex align-items-center py-0">Email</label>
							</div>
							<div class="form-floating mb-20px">
								<input type="password" class="form-control fs-13px h-45px" id="password" name="password" placeholder="Mot de passe" required />
								<label for="password" class="d-flex align-items-center py-0">Mot de passe</label>
							</div>
							<div class="login-buttons">
								<button type="submit" type="submit" name="login" class="btn h-45px btn-outline-pink d-block
								w-100 btn-lg">Connexion</button>
							</div>
                            <div class="m-20px pb-40px text-white">
                                Mot de passe oublié ? <a href="register_v3.html" class="text-pink-500">Cliquez ici</a>
                            </div>
						</form>
						
					</div>
					<!-- END login-content -->
				</div>
				<!-- END login-body -->
			</div>
			<!-- END login-container -->
		</div>
		<!-- END login -->
		
	
		<!-- BEGIN scroll-top-btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
		<!-- END scroll-top-btn -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="assets/js/vendor.min.js"></script>
	<script src="assets/js/app.min.js"></script>
	<!-- ================== END core-js ================== -->
</body>
</html>