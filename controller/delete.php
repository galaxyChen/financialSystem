<?php
include 'model.php';

function delete($id)
{
	$sql=array();
	$sql['table']='student';
	$sql['where']='id';
	$sql['value']=$id;
	mysql_delete($sql);
	$sql['table']='money';
	$sql['where']='sid';
	mysql_delete($sql);
	$sql['table']='family';
	mysql_delete($sql);
	
}

$str='';
$_POST['data']=chop($_POST['data'],' ');
if (strpos($_POST['data'], ' '))
{
	$case=explode(' ', $_POST['data']);
	$n=count($case);
	for ($i=0;$i<$n;$i++)
		delete($case[$i]);
}
else delete($_POST['data']);

?>