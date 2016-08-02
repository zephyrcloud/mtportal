<html>
	<head>
		<title>Menu Desplegable</title>
		<style type="text/css">
			
			* {
				margin:0px;
				padding:0px;
			}
			
			#header {
				margin:auto;
				width:500px;
				font-family:Arial, Helvetica, sans-serif;
			}
			
			ul, ol {
				list-style:none;
			}
			
			.nav > li {
				float:left;
			}
			
			.nav li a {
				background-color:#000;
				color:#fff;
				text-decoration:none;
				padding:10px 12px;
				display:block;
			}
			
			.nav li a:hover {
				background-color:#434343;
			}
			
			.nav li ul {
				display:none;
				position:absolute;
				min-width:140px;
			}
			
			.nav li:hover > ul {
				display:block;
			}
			
			.nav li ul li {
				position:relative;
			}
			
			.nav li ul li ul {
				right:-140px;
				top:0px;
			}
			
		</style>
	</head>
	<body>
		<div id="header">
			<ul class="nav">
				<li><a href="">User</a></li>
				<li><a href="">Domain</a>
					<ul>
						<li><a href="">Lookup</a></li>
						<li><a href="">Manage</a></li>
					</ul>
				</li>
				<li><a href="">Manage Service</a>
					<ul>
						<li><a href="">Transfer Phone Number</a></li>
					</ul>
				</li>
				<li><a href="">User Portals</a>
					<ul>
						<li><a href="">VDI</a></li>
						<li><a href="">Fax</a></li>
						<li><a href="">Voiceemail</a></li>
					</ul>
				</li>
				<li><a href="">Suport</a>
					<ul>
						<li><a href="">New Ticket</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</body>
</html>