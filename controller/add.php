<?php
include 'model.php';
include 'date.php';
$table='student';
$data=array();
if (isset($_POST['name']))
	$data['name']=$_POST['name'];//写入名字
if (isset($_POST['sex']))
	$data['sex']=$_POST['sex'];//写入性别
if (isset($_POST['birth']))
	$data['birth']=$_POST['birth'];//写入生日
if (isset($_POST['class']))
{
	$sql['table']='class';
	$sql['where']='class_name';
	$sql['value']=$_POST['class'];
	$id=mysql_select($sql);
	$data['class']=$id[0]['id'];
	//修改班级的人数
	$class=array();
	$class['table']='class';
	$class['col']='people';
	$class['value']=$id[0]['people']++;
	$class['where']='id';
	$class['case']=$id[0]['id'];
	mysql_update($class);
}//写入班级的id
if (isset($_POST['fphone']))
	{
		$fdata=array();
		$fdata['phone']=$_POST['fphone'];
		$fdata['f_sex']='man';
		if (isset($_POST['fname']))
			$fdata['f_name']=$_POST['fname'];
		mysql_insert($fdata,'family');//添加父亲的信息
		$sql=array();
		$sql['table']='family';
		$sql['where']='phone';
		$sql['value']=$_POST['fphone'];
		$sql['order']='id';
		$sql['order_d']=1;
		$id=mysql_select($sql);
		$data['father']=$id[0]['id'];
	}//写入父亲的电话
if (isset($_POST['mphone']))
	{
		$fdata=array();
		$fdata['phone']=$_POST['mphone'];
		$fdata['f_sex']='women';
		if (isset($_POST['mname']))
			$fdata['f_name']=$_POST['mname'];
		mysql_insert($fdata,'family');//添加母亲的信息
		$sql=array();
		$sql['table']='family';
		$sql['where']='phone';
		$sql['value']=$_POST['mphone'];
		$sql['order']='id';
		$sql['order_d']=1;
		$id=mysql_select($sql);
		$data['mother']=$id[0]['id'];
	}//写入母亲的电话

if (isset($_POST['note']))
	$data['note']=$_POST['note'];//写入备注
$data['type']=$_POST['type'];//写入学生类型
mysql_insert($data,$table);

//选出最后添加的学生的id
$sql=array();
$sql['table']='student';
$sql['where']='name';
$sql['value']=$data['name'];
$sql['order']='id';
$sql['order_d']=1;
$id=mysql_select($sql);

//添加family表的sid
$sql=array();
$sql['table']='family';
$sql['col']='sid';
$sql['value']=$id[0]['id'];
$sql['where']='id';
$sql['case']=$data['father'];
mysql_update($sql);
$sql['case']=$data['mother'];
mysql_update($sql);



//准备处理缴费情况
$data=array();
$data['sid']=$id[0]['id'];
$owe=0;$time=getdate();
$bjf=0;$dsf=0;$hsf=0;
//保教费的情况

$data['bjfr']=$_POST['bjf_r'];$data['bjfg']=$_POST['bjf_g'];
$data['bjfl']=$time['year'].'-'.$time['mon'].'-'.$time['mday'];
if ($data['bjfr']>$data['bjfg'])
{
	$owe+=$data['bjfr']-$data['bjfg'];
	$bjf+=$data['bjfr']-$data['bjfg'];
}
$data['bjfn']=setNext($_POST['bjf_b'],$_POST['bjf_e'],1);

//代收费的情况
$data['dsfr']=$_POST['dsf_r'];$data['dsfg']=$_POST['dsf_g'];
$data['dsfl']=$time['year'].'-'.$time['mon'].'-'.$time['mday'];
if ($data['dsfr']>$data['dsfg'])
{
	$owe+=$data['dsfr']-$data['dsfg'];
	$dsf+=$data['dsfr']-$data['dsfg'];
}
$data['dsfn']=setNext($_POST['dsf_b'],$_POST['dsf_e'],1);

//伙食费的情况
$data['hsfr']=$_POST['hsf_r'];$data['hsfg']=$_POST['hsf_g'];
$data['hsfl']=$time['year'].'-'.$time['mon'].'-'.$time['mday'];
if ($data['hsfr']>$data['hsfg'])
{
	$owe+=$data['hsfr']-$data['hsfg'];
	$hsf+=$data['hsfr']-$data['hsfg'];
}
$data['hsfn']=setNext($_POST['hsf_b'],$_POST['hsf_e'],2);


mysql_insert($data,'money');//缴费信息写入数据库

//写入income表
$sql=array();
$sql['sid']=$data['sid'];
$sql['date']=$time['year'].'-'.$time['mon'].'-'.$time['mday'];
$sql['hsf']=$data['hsfg'];
$sql['dsf']=$data['dsfg'];
$sql['bjf']=$data['bjfg'];
$sql['money']=$data['hsfg']+$data['bjfg']+$data['dsfg'];
$sql['type']=0;
mysql_insert($sql,'income');


//更新学生表的欠费信息
$sql=array();
$sql['table']='student';
$sql['where']='id';
$sql['case']=$id[0]['id'];
$sql['col']='owe bjf_owe dsf_owe hsf_owe';
$sql['value']=$owe.' '.$bjf.' '.$dsf.' '.$hsf;
mysql_update($sql);

echo "<script>alert(添加成功);</script>";
echo '<script>document.location.href="student.html"</script>';
?>