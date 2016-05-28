<?php
include 'model.php';
include 'date.php';

function setDate($begin,$end,$require,$get,$type)
{
	$date=getdate();
	$year=$date['year'];
	$day=$date['mday'];
	$data=array();
	$data[0]=$year.'-'.$begin.'-'.$day;
	if ($require<=$get)
		$data[1]=setNext($begin,$end,$type);
	else $data[1]=getDateNow();
	return $data;
}


function getId($type,$id)
{
	$sql=array();
	$sql['table']='student';
	$sql['where']='id';
	$sql['value']=$id;
	$data=mysql_select($sql);
	if ($type==0)
		return $data[0]['father'];
	else return $data[0]['mother'];
}



function addUpdate($name,$value,&$sql)
{
	if (isset($sql['col']))
	{
		$sql['col'].=' '.$name;
		$sql['value'].=' '.$value;
	}
	else 
	{
		$sql['col']=$name;
		$sql['value']=$value;
	}
}

function findClass($name)
{
	$sql=array();
	$sql['table']='class';
	$sql['where']='class_name';
	$sql['value']=$name;
	$data=mysql_select($sql);
	return $data[0]['id'];
}


$sql=array();
$sql['table']='student';
$url=$_SERVER['HTTP_REFERER'];
$url=explode('=', $url);
$id=$url[1];
$sql['where']='id';
$sql['case']=$id;

//更新student表
addUpdate('name' ,$_POST['name'] ,&$sql);
addUpdate('sex' ,$_POST['sex'] ,&$sql);
$classId=findClass($_POST['class']);
addUpdate('class' ,$classId ,&$sql);
addUpdate('birth' ,$_POST['birth'] ,&$sql);
addUpdate('note' ,$_POST['note'] ,&$sql);
addUpdate('type' ,$_POST['type'] ,&$sql);

$dsf_owe=$_POST['dsf_r']-$_POST['dsf_g'];
$bjf_owe=$_POST['bjf_r']-$_POST['bjf_g'];
$hsf_owe=$_POST['hsf_r']-$_POST['hsf_g'];
$owe=$dsf_owe+$hsf_owe+$bjf_owe;

addUpdate('owe' ,$owe ,&$sql);
addUpdate('dsf_owe' ,$dsf_owe ,&$sql);
addUpdate('hsf_owe' ,$hsf_owe ,&$sql);
addUpdate('bjf_owe' ,$bjf_owe ,&$sql);
mysql_update($sql);

//更新家长表
$sql=array();
$sql['table']='family';
$sql['where']='id';
$fatherId=getId(0,$id);
$sql['case']=$fatherId;
addUpdate('f_name' ,$_POST['fname'] ,&$sql);
addUpdate('phone' ,$_POST['fphone'] ,&$sql);
addUpdate('sid',$id,&$sql);
mysql_update($sql);

$sql=array();
$sql['table']='family';
$sql['where']='id';
$motherId=getId(1,$id);
$sql['case']=$motherId;
addUpdate('sid',$id,&$sql);
addUpdate('f_name' ,$_POST['mname'] ,&$sql);
addUpdate('phone' ,$_POST['mphone'] ,&$sql);

//修改money表

$sql=array();
$sql['table']='money';
$sql['where']='sid';
$sql['case']=$id;

addUpdate('hsfr' ,$_POST['hsf_r'] ,&$sql);
addUpdate('hsfg' ,$_POST['hsf_g'] ,&$sql);
$date=setDate($_POST['hsf_b'],$_POST['hsf_e'],$_POST['hsf_r'],$_POST['hsf_g'],2);
addUpdate('hsfl' ,$date[0] ,&$sql);
addUpdate('hsfn' ,$date[1] ,&$sql);

addUpdate('dsfr' ,$_POST['dsf_r'] ,&$sql);
addUpdate('dsfg' ,$_POST['dsf_g'] ,&$sql);
$date=setDate($_POST['dsf_b'],$_POST['dsf_e'],$_POST['dsf_r'],$_POST['dsf_g'],1);
addUpdate('dsfl' ,$date[0] ,&$sql);
addUpdate('dsfn' ,$date[1] ,&$sql);

addUpdate('bjfr' ,$_POST['bjf_r'] ,&$sql);
addUpdate('bjfg' ,$_POST['bjf_g'] ,&$sql);
$date=setDate($_POST['bjf_b'],$_POST['bjf_e'],$_POST['bjf_r'],$_POST['bjf_g'],1);
addUpdate('bjfl' ,$date[0] ,&$sql);
addUpdate('bjfn' ,$date[1] ,&$sql);

mysql_update($sql);


echo "<script>document.location.href=\"student.html\"</script>";
?>