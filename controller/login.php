<?php
session_start();
include "model.php";
	$sql=array();
	$sql['table']='user';
	$sql['where']='name';
	$sql['value']=$_POST['username'];
	$data=mysql_select($sql);
	if ($data[0]['password']==$_POST['password'])
	{
		$_SESSION['user']=$_POST['username'];
		echo 1;
	}
	else echo 0;


?>