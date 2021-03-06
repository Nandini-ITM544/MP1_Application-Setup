<?php
// Start the session
session_start();
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.
echo $_POST['useremail'];
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}
echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";
require 'vendor/autoload.php';
use Aws\S3\S3Client;
$client = S3Client::factory();
#$s3 = new Aws\S3\S3Client([
 #   'version' => 'latest',
 #   'region'  => 'us-west-2'
#]);
$bucket = uniqid("NandiniMp1",false);
$result = $client->createBucket(array(
    'Bucket' => $bucket
));
# AWS PHP SDK version 3 create bucket
#$result = $s3->createBucket([
#    'ACL' => 'public-read',
#    'Bucket' => $bucket
#]);
$client->waitUntilBucketExists(array('Bucket' => $bucket));
#Old PHP SDK version 2
$key = $uploadfile;
$result = $client->putObject(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket,
    'Key' => $key,
    'SourceFile' => $uploadfile 
));
# PHP version 3
#$result = $s3->putObject([
#    'ACL' => 'public-read',
#    'Bucket' => $bucket,
#   'Key' => $uploadfile
#]);  
$url = $result['ObjectURL'];
echo $url;
use Aws\Rds\RdsClient;
$client = RedClient::factory(array(
'region' => 'us-west-2'
));
#$rds = new Aws\Rds\RdsClient([
#    'version' => 'latest',
#    'region'  => 'us-west-2'
#]);
$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'MP1db',
    #'Filters' => [
    #    [
    #        'Name' => '<string>', // REQUIRED
    #        'Values' => ['<string>', ...], // REQUIRED
    #    ],
        // ...
   # ],
   # 'Marker' => '<string>',
   # 'MaxRecords' => <integer>,
));
$endpoint=" ";
#$endpoint = $result['DBInstances']['Endpoint']['Address']
foreach ($result->getPath('DBInstances/*/Endpoint/Address') as $ep){
echo $ep;
$endpint=$ep;
} 
//echo "begin database";^M
$link = mysqli_connect($endpoint,"nandini90","nandini90","MP1db") or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
/* Prepared statement, stage 1: prepare */
if (!($stmt = $link->prepare("INSERT INTO Project1 (uname,email,phoneforsms,raws3url,finisheds3url,jpegfilename,state,datetime) VALUES (?,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}
$uname="Nandini";
$email = $_POST['useremail'];
$phoneforsms = $_POST['phone'];
$raws3url = $url; //  $result['ObjectURL']; fr
$finisheds3url = "none";
$jpegfilename = basename($_FILES['userfile']['name']);
$state=0;
$datetime=now(;
$stmt->bind_param($uname,$email,$phoneforsms,$raws3url,$finisheds3url,$jpegfilename,$state,$datetime);
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
printf("%d Row inserted.\n", $stmt->affected_rows);
/* explicit close recommended */
$stmt->close();
$link->real_query("SELECT * FROM Project1");
$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo $row['id'] . " " . $row['email']. " " . $row['phoneforsms'];
}
$link->close();
//add code to detect if subscribed to SNS topic 
//if not subscribed then subscribe the user and UPDATE the column in the database with a new value 0 to 1 so that then each time you don't have to resubscribe them
// add code to generate SQS Message with a value of the ID returned from the most recent inserted piece of work
//  Add code to update database to UPDATE status column to 1 (in progress)
?>   