<!-- BEGIN #header -->
		<div id="header" class="app-header">
			<!-- BEGIN navbar-header -->
			<div class="navbar-header pt-5px">
				<a href="index.php" class="navbar-brand"><span class="logo-a2mi50"></span> <b class="me-1">A2MI</b>-Informatique</a>
				<button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile" title="Toggle Mobile Sidebar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- END navbar-header -->
			<!-- BEGIN header-nav -->
			<div class="navbar-nav">
				<div class="navbar-item navbar-user dropdown">

					<a href="profile.php?id=<?= $_SESSION['id']; ?>" class="navbar-link dropdown-toggle d-flex align-items-center"
                       data-bs-toggle="dropdown">
							<span><?= $_SESSION['mail'] ?></span>
							<b class="caret"></b>
					</a>

					<div class="dropdown-menu dropdown-menu-end me-1">
						<a href="profile.php?id=<?= $_SESSION['id']; ?>" class="dropdown-item">Mon compte</a>
						<div class="dropdown-divider"></div>
						<a href="https://www.a2mi-info.com" class="dropdown-item" target="_blank">Retour au site</a>
						<div class="dropdown-divider"></div>
						<a href="logout.php" class="dropdown-item">Déconnexion</a>
					</div>
				</div>
			</div>
			<!-- END header-nav -->
		</div>
		<!-- END #header -->