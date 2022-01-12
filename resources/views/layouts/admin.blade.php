<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>@yield('title')</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/images/icon.ico" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/atlantis.min.css">
	<link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/styles.css">


	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="https://javier9818.github.io/cdn.tenvio/sudunt/assets/css/demo.css">
    @yield('style')
</head>
<body>
	<div class="wrapper">
        @include('components.nav')
		<!-- Sidebar -->
		@yield('side-nav')
		<!-- End Sidebar -->

		<div class="main-panel" id="app">
			@yield('content')
			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul class="nav">
							<li class="nav-item">
								<a class="nav-link" href="javascript:void(0)">
									SUDUNT
								</a>
							</li>
							<!-- <li class="nav-item">
								<a class="nav-link" href="javascript:void(0)">
									Help
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="javascript:void(0)">
									Licenses
								</a>
							</li> -->
						</ul>
					</nav>
					<div class="copyright ml-auto">
						Copyright@2022
					</div>
				</div>
			</footer>
        </div>

	</div>
	<script src="/js/app.js"></script>
	<!--   Core JS Files   -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/core/popper.min.js"></script>
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/core/bootstrap.min.js"></script>

	<!-- jQuery UI -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


	<!-- Chart JS -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Bootstrap Notify -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

	<!-- jQuery Vector Maps -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

	<!-- Sweet Alert -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Atlantis JS -->
	<script src="https://javier9818.github.io/cdn.tenvio/sudunt/assets/js/atlantis.min.js"></script>
	@yield('script')
</body>
</html>
