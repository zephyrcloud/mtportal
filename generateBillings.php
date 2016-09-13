<?php
	include("config/connection.php");
	include("api_vetality.php");
	include("config/ip_capture.php");
	include("billings.php");
	
	$api = new api_vetality();
	$ip_capture = new ip_capture();
	$billing = new billings();
	
	if (isset($_POST["Month"])) {
		$year = $_POST["Year"];
		$month = $_POST["Month"];
		echo $billing->inbound($month,$year);		
	}
?>

<html>
					<head>
						<title>Generate Billings </title>
						 <link href="style/style.css" rel="stylesheet" type="text/css">
							<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
							<script>
								function goTo(destination) {
									window.location.href = destination;
								}								
							</script>
							<!-- JQuery UI -->
							<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
							<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>


						<!-- JQuery UI -->
						<!--<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
						<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>-->
						
					</head>
					<body>
						
						<?php
							include("header.php");
							include("menuadmin.php");
						?>
						
						<div id="pagecontents">
							<div class="wrapper" >
								<div id="post">
									 <div id="postitle">
										<div class="floatleft"><h1>Generate Billings</h1></div>
										<div class="floatright righttext tpad"></div>
										<div class="clear">&nbsp;</div>
									</div> 
									<br/>
										Select Month and year to proccess
										<form method="POST" action="generateBillings.php">		
											<div>
											<br>
												<select id="Month" name="Month" >
													<?php
													$months=Array("January","February","March","April","May","June","July","August","September","October","November","December");
													for($i=0; $i < 12 ;$i++){
														if(i < 9){
															echo "<option value='0".($i+1)."'>".$months[$i]."</option>";
														}else{
															echo "<option value='".($i+1)."'>".$months[$i]."</option>";
														}
													}
													?>
												 </select>
												 <select id="Year" name="Year" >
													<?php
													for($i=2013; $i < date("Y")+1 ;$i++){
														if($i == date("Y")){
															$selected = "selected";
														}
														echo "<option $selected value='".$i."'>".$i."</option>";
													}
													?>
												 </select>
											 </div>
											 <br>
											 <input id="generate" type="submit" value="Generate" >
										</form> 
								</div>
							</div>
						</div>

						
				<?php
					include("footer.php");
					
				?>
					</body>
			</html>