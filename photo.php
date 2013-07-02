<?php include("php/header.php"); ?>
<?php
include("php/dbconnect.php");
session_start();if($_SESSION['logged_in']==true){
//Check to resize photo
if(isset($_POST['v3'])&&$_POST['v3']!=""){
	$ext = substr($_FILES['userfile']['name'],-4);
	$filename = $_POST['v3'].$ext;
	$uploaddir = 'photos/temp/';
	$uploaddirnew = 'photos/';
	$uploadfile = $uploaddir . basename($filename);
	$uploadfilenewDir = $uploaddir . basename($filename);

	if($ext==".jpg"){
		//print(ini_get('upload_max_filesize')); print_r($_FILES['userfile']);
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			$im = ImageCreateFromJpeg($uploadfile);
			if(imagesx($im)>imagesy($im)){$im=imagerotate($im, 270, 0);}
			
			$ox = imagesx($im);
			$oy = imagesy($im);
			
			
			
			$height = 287;
			$width = 214;
			if($ox < $oy){
			   $ny = $height;
			   $nx = floor($ox * ($ny / $oy)); 
			} else {
			   $nx = $width;
			   $ny = floor($oy * ($nx / $ox)); 
			} 
			$nm = imagecreatetruecolor($nx, $ny);
			imagecopyresized($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);
			imagejpeg($nm, $uploaddirnew.$filename, 100);
			
			//Update Database
			$sql = "UPDATE [CCA_EVMS].[dbo].[EVMS_volunteer] SET [volunteer_photo]='".$filename."' WHERE [volunteer_id]=".$_POST['v3'];
			$stmt = sqlsrv_query($conn,$sql);
			if( $stmt === false) {
				die( print_r( sqlsrv_errors(), true) );
			}
			
		} else {
			//Error 2 - Error in Image file
			print_r($_FILES['userfile']);
			//Header("Location:error.php?err=2");
		}
	} else {
		//Error 3 - Not a proper image file
		Header("Location:error.php?err=3");
	}
}
if(isset($_POST['ctl00'])&&$_POST['ctl00']!="") {
	
	//Check to see if the Barcode exists
	$val=$_POST['ctl00'];
	$sql = "SELECT [volunteer_id] FROM [dbo].[EVMS_volunteer_event] WHERE [volunteer_accred_num]='".$val."'";
	$stmt = sqlsrv_query($conn,$sql);
	if( $stmt === false) {
		$v="";
		/*die( print_r( sqlsrv_errors(), true) );*/
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
		$v=$row[0];
	}
	
	//Check for picture
	if($v!=""){
		$sql = "SELECT [volunteer_photo] FROM [CCA_EVMS].[dbo].[EVMS_volunteer] WHERE [volunteer_id]=".$v;
		if( $stmt === false) {
			$photoID="";
		}
		$stmt = sqlsrv_query($conn,$sql);
		while( $row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
			$photoID= $row2[0];
			$filename = 'photos/'.$photoID;
			if (!file_exists($filename)) {
				$photoID= "default_s_ss.png";
			}
		}
		if($photoID!=""){
?>
			<div id="topbar">
				<div id="home_btn"><a href="scan.php">Home</a></div>
				<div id="title_r"><?php 
				$sql = "SELECT [profile_id] FROM [CCA_EVMS].[dbo].[EVMS_volunteer] WHERE [volunteer_id]=".$v;
				if( $stmt === false) {}
				$stmt = sqlsrv_query($conn,$sql);
				while( $rowfindID = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
					$sql2 = "SELECT [first_name],[last_name] FROM [CCA_EVMS].[dbo].[EVMS_profile] WHERE [profile_id]={$rowfindID[0]}";
					$stmt2 = sqlsrv_query( $conn, $sql2);
					if( $stmt2 === false ) {die( print_r( sqlsrv_errors(), true));}
					if( sqlsrv_fetch( $stmt2 ) === false) {die( print_r( sqlsrv_errors(), true));}
					$fname = sqlsrv_get_field( $stmt2, 0);
					$lname = sqlsrv_get_field( $stmt2, 1);
					echo $fname." ".$lname;
				}
			?></div>
			</div>
			<div id="photoBox">
				<div id="imageBox"><img src="photos/<?php echo $photoID."?".time(); ?>" /></div>
				<form name="logForm2" method="post" action="kiosk.php" id="logForm">
					<input type="submit" value="Use this Photo" class="sub_btn_r" />
					<input type="hidden" name="v" id="v" value="<?php echo $v; ?>" />
					<input type="hidden" name="e" id="e" value="<?php $sql = "SELECT [event_id] FROM [CCA_EVMS].[dbo].[EVMS_volunteer_event] WHERE [volunteer_accred_num]='".$_POST['ctl00']."'";
							$stmt = sqlsrv_query($conn,$sql);
							while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
							echo $row[0];
					} ?>" />
				</form>
				<form name="logFormNewImage" enctype="multipart/form-data" method="post" action="photo.php" id="logFormNewImage" class="spacing">
					<div class="sub_btn_g" style=" margin-top:8px; padding-top:10px;">Take a new Photo<input id="userfile" name="userfile" type="file" class="b_input2" value="" /></div>
					<input type="hidden" name="v3" id="v3" value="<?php echo $v; ?>" />
					<input type="hidden" name="ctl00" id="ctl00" value="<?php echo $_POST['ctl00']; ?>" />
					<input type="submit" class="hidden" />
				</form>
			</div>
			
			


<?php	} else { ?>


			<div id="topbar">
				<div id="home_btn"><a href="scan.php">Home</a></div>
				<div id="title_r"><?php 
				$sql = "SELECT [profile_id] FROM [CCA_EVMS].[dbo].[EVMS_volunteer] WHERE [volunteer_id]=".$v;
				if( $stmt === false) {}
				$stmt = sqlsrv_query($conn,$sql);
				while( $rowfindID = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
					$sql2 = "SELECT [first_name],[last_name] FROM [CCA_EVMS].[dbo].[EVMS_profile] WHERE [profile_id]={$rowfindID[0]}";
					$stmt2 = sqlsrv_query( $conn, $sql2);
					if( $stmt2 === false ) {die( print_r( sqlsrv_errors(), true));}
					if( sqlsrv_fetch( $stmt2 ) === false) {die( print_r( sqlsrv_errors(), true));}
					$fname = sqlsrv_get_field( $stmt2, 0);
					$lname = sqlsrv_get_field( $stmt2, 1);
					echo $fname." ".$lname;
				}
			?></div>
			</div>
			<div id="photoBox">
				<div class="blueGreyText" style="margin:100px 0;">We don't have a photo on file for you.</div>
				<form name="logFormNewImage" enctype="multipart/form-data" method="post" action="photo.php" id="logFormNewImage">
					<div class="sub_btn_g" style=" margin-top:8px; padding-top:10px;">Take a new Photo<input id="userfile" name="userfile" type="file" class="b_input2" value="" /></div>
					<input type="hidden" name="v3" id="v3" value="<?php echo $v; ?>" />
					<input type="hidden" name="ctl00" id="ctl00" value="<?php echo $_POST['ctl00']; ?>" />
					<input type="submit" class="hidden" />
				</form>
			</div>
<?php	} ?>

<?php 
	} else {
		//Error 1 - Invalid Barcode
		Header("Location:error.php?err=1");
	}
} else {
	//Error 4 - No Barcode
	Header("Location:error.php?err=4");
}
?>
	<script type="text/javascript">
		$('#userfile').change(function() {
			$('#logFormNewImage').submit();
		});
	</script>
	<?php } else { header("Location:error.php?err=5"); }?>
<?php include("php/footer.php"); ?>