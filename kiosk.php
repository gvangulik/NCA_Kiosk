<?php include("php/header.php"); ?>
<?php session_start();if($_SESSION['logged_in']==true){ ?>
<?php
if(isset($_POST['v'])&&$_POST['v']!=""){
 ?>
 <?php 
include("php/dbconnect.php");

$e=$_POST["e"];
$v=$_POST["v"];

if(isset($_POST["s"])&&$_POST["s"]!=""){
	foreach($_POST["sel"] as $k=>$l){
		if($l=="0"){$l="";}
		//Checks if it exists in the DB
		$stmt = sqlsrv_query( $conn, "SELECT [uniform_size] FROM [CCA_EVMS].[dbo].[EVMS_volunteer_uniform] WHERE [volunteer_id]=$v AND [uniform_id]=$k");
		if ($stmt) {
			$rows = sqlsrv_has_rows( $stmt );
			if ($rows === true){
				//Update Database
				$sql = "UPDATE [CCA_EVMS].[dbo].[EVMS_volunteer_uniform] SET [uniform_size]='".$l."' WHERE [uniform_id]=".$k." AND [volunteer_id]=".$_POST['v'];

				$stmt = sqlsrv_query($conn,$sql);
				if( $stmt === false) {
					die( print_r( sqlsrv_errors(), true) );
				}
			} else {
				//Insert into Database
				$sql = "INSERT INTO [CCA_EVMS].[dbo].[EVMS_volunteer_uniform] ([event_id],[uniform_id],[volunteer_id],[uniform_size],[issued]) VALUES ($e,$k,$v,'$l',0)";

				$stmt = sqlsrv_query($conn,$sql);
				if( $stmt === false) {
					die( print_r( sqlsrv_errors(), true) );
				}
			}
		}
		
		
	}?>
	<div id="topbar">
		<div id="home_btn"><a href="scan.php">Home</a></div>
		<div id="title_r">
			<?php 
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
			?>
		</div>
	</div>
	<div id="confirmBox">
		<div id="confirmWrapper">
			<div id="imageBox">
				<?php
						$sql = "SELECT [volunteer_photo] FROM [CCA_EVMS].[dbo].[EVMS_volunteer] WHERE [volunteer_id]=".$v;
						if( $stmt === false) {
							$photoID="";
						}
						$stmt = sqlsrv_query($conn,$sql);
						while( $row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
							$photoID= $row2[0];
							$filename = 'photos/'.$photoID;
							if (!file_exists($filename)) {
								$photoID= "default.png";
							}
						}
				?>
				<img src="photos/<?php echo $photoID."?".time(); ?>" />
			</div>
			<div id="completedCheck"></div>
		</div>
		<div id="uniWrapper" class="input-wrapper">
			<?php $sql = "SELECT [uniform_id],[uniform_desc] FROM [CCA_EVMS].[dbo].[EVMS_uniform_piece] WHERE [event_id]=".$e;
				$stmt = sqlsrv_query($conn,$sql);
				if($stmt === false) {}
					$i=0;
					while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)){
						$size="";
				
						$sql2 = "SELECT [uniform_size] FROM [CCA_EVMS].[dbo].[EVMS_volunteer_uniform] WHERE [volunteer_id]=$v AND [uniform_id]={$row[0]}";
						$stmt2 = sqlsrv_query( $conn, $sql2);
						if( $stmt2 === false ) {die( print_r( sqlsrv_errors(), true));}
						if( sqlsrv_fetch( $stmt2 ) === false) {die( print_r( sqlsrv_errors(), true));}
						$size = sqlsrv_get_field( $stmt2, 0);
						if($size==""){$size="None Selected";}
						echo <<<PRE
						<div class="selChoices">
							<label>{$row[1]}</label>
							<div class='btnHolder'>
								<span class='size size_{$row[0]}' name='size_{$row[0]}'>$size</span><input type="hidden" name="sel[{$row[0]}]" id="sel[{$row[0]}]" class="valHid_{$row[0]}" value="$size" />
							</div>
						</div>
								
PRE;
						$i++; 
					} ?>
		</div>
		<div class="menu">
			<form name="logFormFinal" method="post" action="scan.php" id="logFormFinal">
				<input name="ctl00" type="submit" value="Scan another Barcode" class="sub_btn_g spacing" />
			</form>
		</div>
	</div>

<?php } else { ?>
	<div id="topbar">
		<div id="home_btn"><a href="scan.php">Home</a></div>
		<div id="title_r"><?php 
				$sql = "SELECT [profile_id] FROM [CCA_EVMS].[dbo].[EVMS_volunteer] WHERE [volunteer_id]=".$v;
				$stmt = sqlsrv_query($conn,$sql);
				if( $stmt === false) {}
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
	<div id="uniformBox">
		<div id="blueGreyText">Select uniform sizes</div>
		<form name="logFormFinal" method="post" action="kiosk.php" id="logFormFinal">
		<div id="uniWrapper" class="input-wrapper">
			
					<?php $sql = "SELECT [uniform_id],[uniform_desc] FROM [CCA_EVMS].[dbo].[EVMS_uniform_piece] WHERE [event_id]=".$e;
						  $stmt = sqlsrv_query($conn,$sql);
						  if($stmt === false) {}
						  $i=0;
						  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)){
								$size="";
								$sql2 = "SELECT [uniform_size] FROM [CCA_EVMS].[dbo].[EVMS_volunteer_uniform] WHERE [volunteer_id]=$v AND [uniform_id]={$row[0]}";
								$stmt2 = sqlsrv_query( $conn, $sql2);
								if( $stmt2 === false ) {die( print_r( sqlsrv_errors(), true));}
								if( sqlsrv_fetch( $stmt2 ) === false) {die( print_r( sqlsrv_errors(), true));}
								$size = sqlsrv_get_field( $stmt2, 0);
								if($size==""){$size="None";}
								if($size=="None"){$sizeVal=0;} else
								if($size=="XS"){$sizeVal=1;} else
								if($size=="S"){$sizeVal=2;} else
								if($size=="M"){$sizeVal=3;} else
								if($size=="L"){$sizeVal=4;} else
								if($size=="XL"){$sizeVal=5;} else
								if($size=="XL2"){$sizeVal=6;} else
								if($size=="XL3"){$sizeVal=7;} else
								if($size=="XL4"){$sizeVal=8;}
								echo <<<PRE
<div class="selChoices">
						<label>{$row[1]}</label>
						<script language="JavaScript">

							var sizes_{$row[0]} = ["None", "XS", "S", "M", "L", "XL", "XL2", "XL3", "XL4"];
							var currentSizeVal_{$row[0]} = $sizeVal;
							$(function(){
								$("a.lower_{$row[0]}").click(function(){
									 event.stopPropagation(); event.preventDefault();
									if(currentSizeVal_{$row[0]}!=0){
											currentSizeVal_{$row[0]} = currentSizeVal_{$row[0]}-1
											$(".size_{$row[0]}").html(sizes_{$row[0]}[currentSizeVal_{$row[0]}]);
											$(".valHid_{$row[0]}").val(sizes_{$row[0]}[currentSizeVal_{$row[0]}]);
											return false;
										}
								});
								$("a.higher_{$row[0]}").click(function(){
									 event.stopPropagation(); event.preventDefault();
									if(currentSizeVal_{$row[0]}!=8){
											currentSizeVal_{$row[0]} = currentSizeVal_{$row[0]}+1
											$(".size_{$row[0]}").html(sizes_{$row[0]}[currentSizeVal_{$row[0]}]);
											$(".valHid_{$row[0]}").val(sizes_{$row[0]}[currentSizeVal_{$row[0]}]);
											return false;
										}
								});
							});
						</script>
						
						<div class='btnHolder'>
							<a href='#' class='lowerBtn lower_{$row[0]}' name='lower_{$row[0]}'></a>
							<span class='size size_{$row[0]}' name='size_{$row[0]}'>$size</span><input type="hidden" name="sel[{$row[0]}]" id="sel[{$row[0]}]" class="valHid_{$row[0]}" value="$size" />
							<a href='#' class='higherBtn higher_{$row[0]}' name='higher_{$row[0]}'></a>
						</div>
					</div>
								
PRE;
								?>
					<?php $i++; } ?>
		</div>
		<input name="ctl00" type="submit" value="Save Uniform Selections" class="sub_btn_g spacing" />
				<input type="hidden" name="v" id="v" value="<?php echo $_POST['v']; ?>" />
				<input type="hidden" name="e" id="e" value="<?php echo $_POST['e']; ?>" />
				<input type="hidden" name="s" id="s" value="1" />
				<input type="hidden" name="timestamp" id="timestamp" value="<?php echo date("Y-m-d H:i:s"); ?>" />
			</form>
	</div>
<?php } ?>
	
<?php include("php/footer.php"); ?>
<?php 
} else {
	//Error 5 - Not logged in
	Header("Location:error.php?err=5");
}
?>
<?php } else { header("Location:error.php?err=5"); }?>