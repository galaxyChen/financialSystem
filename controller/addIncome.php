<?php
include 'model.php';
include 'date.php';

function insertIncome($data,$type)
{
	$sql=array();
	$sql['date']=$data['date'];
	$sql['money']=$data['money'];
	$sql['type']=$type;
	$sql['note']=$data['note'];
	switch ($type) 
	{
		case 1:$sql['dsf']=$data['dsf'];break;
		case 2:$sql['bjf']=$data['bjf'];break;
		case 3:$sql['hsf']=$data['hsf'];break;
	}
	$sql['sid']=$data['sid'];
	mysql_insert($sql,'income');
}



function addBjf($data)//data是post过来的值
{
	$sql=array();
	$sql['table']='money';
	$sql['where']='sid';
	$sql['value']=$data['sid'];
	$money=mysql_select($sql);
	$money=$money[0];//获得缴费信息表
	//修改money表
	$sql=array();
	$sql['table']='money';
	$sql['where']='sid';
	$sql['case']=$data['sid'];
	$sql['col']='bjfg bjfl bjfn';
	$bjfg=$money['bjfg']+$data['money'];
	$bjfl=$data['date'];
	$time=getdate();$mon_b=$time['mon'];
	if ($mon_b>=9||$mon_b<2) $mon_e=1;
	else $mon_e=8;
	if ($bjfg>=$money['bjfr'])
		$bjfn=setNext($mon_b,$mon_e,1);
	else $bjfn=$bjfl;
	$sql['value']=$bjfg.' '.$bjfl.' '.$bjfn;
	mysql_update($sql);
	//修改student表
	$sql=array();
	$sql['table']='student';
	$sql['where']='id';
	$sql['value']=$data['sid'];
	$student=mysql_select($sql);
	$student=$student[0];
	if ($student['bjf_owe']>0)
	{
		$owe=$student['owe']-$data['money'];
		$bjf_owe=$student['bjf_owe']-$data['money'];
		$sql['col']='owe bjf_owe';
		$sql['value']=$owe.' '.$bjf_owe;
		$sql['case']=$data['sid'];
		mysql_update($sql);
	}
	//写入income表
		$sql=array();
		$sql['bjf']=$data['money'];
		$sql['money']=$data['money'];
		$sql['date']=$data['date'];
		$sql['sid']=$data['sid'];
		insertIncome($sql,2);
		echo "修改成功！";
}

function addDsf($data)//data是post过来的值
{
	$sql=array();
	$sql['table']='money';
	$sql['where']='sid';
	$sql['value']=$data['sid'];
	$money=mysql_select($sql);
	$money=$money[0];//获得缴费信息表
	//修改money表
	$sql=array();
	$sql['table']='money';
	$sql['where']='sid';
	$sql['case']=$data['sid'];
	$sql['col']='dsfg dsfl dsfn';
	$dsfg=$money['dsfg']+$data['money'];
	$dsfl=$data['date'];
	$time=getdate();$mon_b=$time['mon'];
	if ($mon_b>=9||$mon_b<2) $mon_e=1;
	else $mon_e=8;
	if ($dsfg>=$money['dsfr'])
		$dsfn=setNext($mon_b,$mon_e,1);
	else $dsfn=$dsfl;
	$sql['value']=$dsfg.' '.$dsfl.' '.$dsfn;
	mysql_update($sql);
	//修改student表
	$sql=array();
	$sql['table']='student';
	$sql['where']='id';
	$sql['value']=$data['sid'];
	$student=mysql_select($sql);
	$student=$student[0];
	if ($student['dsf_owe']>0)
	{
		$owe=$student['owe']-$data['money'];
		$dsf_owe=$student['dsf_owe']-$data['money'];
		$sql['col']='owe dsf_owe';
		$sql['value']=$owe.' '.$dsf_owe;
		$sql['case']=$data['sid'];
		mysql_update($sql);
	}
	//写入income表
		$sql=array();
		$sql['dsf']=$data['money'];
		$sql['money']=$data['money'];
		$sql['date']=$data['date'];
		$sql['sid']=$data['sid'];
		insertIncome($sql,2);
		echo "修改成功！";
}

function addHsf($data)
{
	//1.改变student表 2.改变money表 3.写入income表
	$sql=array();
	$sql['table']='student';
	$sql['where']='id';
	$sql['value']=$data['sid'];
	$student=mysql_select($sql);
	$student=$student[0]; //取回原来的student信息
	$sql['table']='student';
	$sql['case']=$data['sid'];
	$sql['col']='owe hsf_owe';
	$owe=$student['owe'];
	$hsf_owe=$student['hsf_owe'];
	if ($hsf_owe>0)
	{
		$owe-=$data['money'];
		$hsf_owe-=$data['money'];
	}
	$sql['value']=$owe.' '.$hsf_owe;
	mysql_update($sql);//更新student信息

	$sql['table']='money';
	$sql['where']='sid';
	$sql['value']=$data['sid'];
	$money=mysql_select($sql);//取回money信息
	$sql=array();
	$sql['table']='money';
	$sql['where']='sid';
	$sql['case']=$data['sid'];
	$sql['col']='hsfl hsfn';
	$hsfl=$data['date'];
	if ($hsf_owe<=0) $hsfn=setNext($data['timeb'],$data['timee'],2);
	else $hsfn=$hsfl;
	$sql['value']=$hsfl.' '.$hsfn;
	mysql_update($sql);//更新money信息


	$sql=array();
	$sql['hsf']=$data['money'];
	$sql['money']=$data['money'];
	$sql['date']=$data['date'];
	$sql['sid']=$data['sid'];
	insertIncome($sql,3);
	echo "修改成功！";
}


switch ($_POST['p']) {
	case 1:addBjf($_POST);break;
	case 2:addDsf($_POST);break;
	case 3:addHsf($_POST);break;
}

?>