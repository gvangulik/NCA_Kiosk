<?php include("php/dbconnect.php"); ?>
<?php include("php/header.php"); ?>
<?php session_start();if($_SESSION['logged_in']==true){ ?>
<div id="topbar">
		<div id="title">Welcome</div>
	</div>
	<div id="logoHolder">	
		<div id="logo"></div>
	</div>
	<div id="scanBox">	
		<form name="logForm" method="post" action="photo.php" id="logForm">
			<label>SCAN or ENTER BARCODE</label>
			<div class="input-wrapper loginmargins"><input name="ctl00" type="text" id="barInput keyword" class="" value="" autocorrect="off" autocapitalize="off" autocomplete="off" /></div>
			<input type="submit" value="Submit" class="sub_btn_g spacing" />
			<script language="JavaScript">
				document.getElementById('barInput').focus();
			</script>
		</form>
	</div>
<?php } else { header("Location:error.php?err=5"); }?>
<?php include("php/footer.php"); ?>