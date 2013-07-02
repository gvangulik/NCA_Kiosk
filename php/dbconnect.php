<?php
$serverName = "FNET\SQLEXPRESS3";
$connectionOptions = array("Database" => "CCA_EVMS", 
                           "UID" => "sa",
                           "PWD" => "sqldamascus!242",
                           "MultipleActiveResultSets" => true);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn === false)
{
     die(print_r(sqlsrv_errors(), true));
}
?>
