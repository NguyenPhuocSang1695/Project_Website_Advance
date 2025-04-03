<?php
$myconn= new mysqli('localhost','root','','webdb');
if($myconn){
  mysqli_query($myconn,"SET NAMES 'UTF8' ");
  // echo "connect successful";
}
else {
  echo "Try again";
};