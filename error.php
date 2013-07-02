<?php include("php/header.php"); ?>
<?php 
include("php/dbconnect.php");
$return = "scan.php";
if(!isset($_GET['err'])){
	$title="Oops!";
	$description="Seems you've gotten here by mistake! <br /><br />The page will automatically redirect shortly.";
} else {
	if($_GET['err']=="1"){
		$title="<strong>Error - Invalid Barcode</strong>";
		$description="The barcode you have entered isn't in our database! <br /><br />The page will automatically redirect shortly.";
	} elseif($_GET['err']=="2"){
		$title="<strong>Error - Error in transfer</strong>";
		$description="The picture you sent could not be received.  <br /><br />The page will automatically redirect shortly.";
	} elseif($_GET['err']=="3"){
		$title="<strong>Error - Not an image file!</strong>";
		$description="Only image files may be transfered.  <br /><br />The page will automatically redirect shortly.";
	} elseif($_GET['err']=="4"){
		$title="<strong>Error - No Barcode entered</strong>";
		$description="Please enter a valid barcode!  <br /><br />The page will automatically redirect shortly.";
	} elseif($_GET['err']=="5"){
		$title="<strong>Error - Not logged in</strong>";
		$description="You are no longer logged in! Please try again.  <br /><br />The page will automatically redirect shortly.";
		$return="index.php";
	} else {
		$title="<strong>Oops!</strong>";
		$description="Seems you've gotten here by mistake!  <br /><br />The page will automatically redirect shortly.";
	}
}

?>
	<div id="topbar">
				<div id="title">Error!</div>
			</div>
			<div id="photoBox">
				<div class="blueGreyText" style="margin:25px 0;"><?php echo $title; ?></div>
				<div class="blueGreyText" style="margin:25px 0;"><?php echo $description; ?></div>
			</div>
	<script language="JavaScript">
					function goHome() {
						window.location.href='<?php echo $return; ?>';
					}
					setTimeout('goHome()', 5000);  
				</script>
<?php include("php/footer.php"); ?>
