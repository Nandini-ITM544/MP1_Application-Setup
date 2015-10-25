

<?php
$sqli_conn = new mysqli("mp1db.c7brzs9x3acd.us-west-2.rds.amazonaws.com","nandini90","nandini90","MP1db",3306) or die("Error " . mysqli_error($link)); 

$res = $sqli_conn->query("CREATE TABLE Project1 
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
uname VARCHAR(20),
email VARCHAR(20),
phoneforsms VARCHAR(20),
raws3url VARCHAR(256),
finisheds3url VARCHAR(256),
jpegfilename VARCHAR(256),
state tinyint(3) CHECK(state IN (0,1,2)),
datetime timestamp
)");
$output=shell_exec("chmod 600 setup.php");
?>
