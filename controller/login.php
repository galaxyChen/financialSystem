<?php
session_start();
if ($POST['p']==0&&$_SESSION['user']=='')
{
if ($_POST['password']=='admin')
	{
		echo 1;
		$_SESSION['user']='admin';
	}
else echo 0;
}

if ($_GET['p']==1)
	if ($_SESSION['user']!='')
		echo $_SESSION['user'];
	else echo 0;


?>