<?php
	$url='localhost:3306';
	$username='suroot';
	$password='suroot123';
	$conn=mysqli_connect($url,$username,$password,"booking2");
	if(!$conn){
	 die('Could not Connect My Sql:' .mysql_error());
	}

	$sql = "DELETE FROM ci_sessions ";
	if (mysqli_query($conn, $sql)) {
	    echo "Record deleted successfully";
	} else {
	    echo "Error deleting record: " . mysqli_error($conn);
	}
	mysqli_close($conn);
?>