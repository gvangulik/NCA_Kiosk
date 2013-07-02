<?php include("php/dbconnect.php"); ?>
<?php include("php/config.php"); ?>
<?php 	$error="";
		if(isset($_POST['uid'])&&($_POST['uid']!=$userID||$_POST['pid']!=$passID)){$error="Please enter a valid Username and Password";}
		if(isset($_POST['uid'])&&($_POST['uid']==$userID||$_POST['pid']==$passID)){session_start(); $_SESSION['logged_in']=true; header("Location:scan.php");}
?>
<?php include("php/header.php"); ?>
	<div id="topbar">
		<div id="title">Login</div>
	</div>
	<div id="logoHolder">	
		<div id="logo"></div>
	</div>
	<div id="loginBox">	
		<div class="blueGreyText" style="margin:50px 0;"><?php echo $error; ?></div>
		<form name="logForm" method="post" action="index.php" id="logForm">
			<label>Username</label>
			<div class="input-wrapper loginmargins"><input required type="text" name="uid" id="uid keyword" autocorrect="off" autocapitalize="off" autocomplete="off" /></div>
			
			<label>Password</label>
			<div class="input-wrapper loginmargins"><input required type="password" name="pid" id="pid keyword" autocorrect="off" autocapitalize="off" autocomplete="off" /></div>

			<input type="submit" class="sub_btn spacing" />
		</form>
	</div>
<?php include("php/footer.php"); ?>