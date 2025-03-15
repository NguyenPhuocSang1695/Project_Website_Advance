<?php
$server="localhost";
$user="root";
$password="";
$database="business_db";//quanlydonhang

$myconn =new mysqli($server,$user,$password,$database);
if($myconn){
  mysqli_query($myconn,"SET NAMES 'UTF8' ");
  echo "connect successful";
}
else {
  echo "Try again";
};

?>  