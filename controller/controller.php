<?php
session_start();
include 'model.php';
function basicInfor()
{
	$data=array();
	$data['username']=$_SEESION['user'];
	$sql=array();
	$data['student']=mysql_count('student');//获取学生人数
	$data['class']=mysql_count('class');//获取班的数目
	$sql['table']='gg';
	$data['gg']=mysql_select($sql);
	echo json_encode($data);
}

function studentInfor($page)
{
	$begin=($page-1)*15;
	$sql['table1']='student';
	$sql['table2']='class';
	$sql['value1']='class';
	$sql['value2']='id';
	$sql['limit']=$begin.' 15';
	$sql['order']='student.id';
	$sql['order_d']='DESC';
	$data=mysql_select_joinAll($sql);
	echo json_encode($data);
}

function oneStudent($id)
{
	$data=array();
	$sql['table1']='student';
	$sql['table2']='class';
	$sql['value1']='class';
	$sql['value2']='id';
	$sql['where']='student.id';
	$sql['value']=$id;
	$stu_cla=mysql_select_joinAll($sql);//student表和class表
	$data['name']=$stu_cla[0]['name'];
	$data['sex']=$stu_cla[0]['sex'];
	$data['birth']=$stu_cla[0]['birth'];
	$data['class']=$stu_cla[0]['class_name'];
	$data['note']=$stu_cla[0]['class'];
	$data['type']=$stu_cla[0]['type'];

	$sql['table1']='family';
	$sql['table2']='money';
	$sql['value1']='sid';
	$sql['value2']='sid';
	$sql['where']='money.sid';
	$sql['value']=$id;
	$mon_fam=mysql_select_joinAll($sql); //money表和family表
	$data['fname']=$mon_fam[0]['f_name'];
	$data['mname']=$mon_fam[1]['f_name'];
	$data['fphone']=$mon_fam[0]['phone'];
	$data['mphone']=$mon_fam[1]['phone'];

	$data['bjfr']=$mon_fam[0]['bjfr'];$data['bjfg']=$mon_fam[0]['bjfg'];
	$data['bjfl']=$mon_fam[0]['bjfl'];$data['bjfn']=$mon_fam[0]['bjfn'];
	$data['dsfr']=$mon_fam[0]['dsfr'];$data['dsfg']=$mon_fam[0]['dsfg'];
	$data['dsfl']=$mon_fam[0]['dsfl'];$data['dsfn']=$mon_fam[0]['dsfn'];
	$data['hsfr']=$mon_fam[0]['hsfr'];$data['hsfg']=$mon_fam[0]['hsfg'];
	$data['hsfl']=$mon_fam[0]['hsfl'];$data['hsfn']=$mon_fam[0]['hsfn'];
	
	echo json_encode($data);
}

function multipleCheck()
{
	$sql=array();
	$sql['table']='student';
	if (isset($_GET['class']))
	{
		$sql['where']='class';
		$sql['operator']='=';
		$sql['value']=$_GET['class'];
	}
	if (isset($_GET['owe']))
		if (isset($sql['where']))
		{
			$sql['where']=$sql['where'].' owe';
			if ($_GET['owe'])
				$sql['operator']=$sql['operator'].' > ';
			else $sql['operator']=$sql['operator'].' = ';
			$sql['value']=$sql['value'].' 0';
		}
		else 
		{
			$sql['where']='owe';
			if ($_GET['owe'])
				$sql['operator']='>';
			else $sql['operator']='=';
			$sql['value']=0;
		}
	$sql['order']='owe';$sql['order_d']=1;
	$data=mysql_select($sql);
	echo json_encode($data);
	
}

function findOneStudent($name)
{
	$sql=array();
	$sql['table']='student';
	$sql['where']='name';
	$sql['value']=$name;
	$data=mysql_select($sql);
	if ($data)
		echo json_encode($data);
	else echo 0;
}

function classInfor($id)
{
	$sql['table']='class';
	$sql['where']='id';
	$sql['value']=$id;
	$data=mysql_select($sql);
	echo json_encode($data);
}

function findStudentInClass($id)
{
	$sql['table']='student';
	$sql['where']='class';
	$sql['value']=$id;
	$data=mysql_select($sql);
	echo json_encode($data);
}


function incomeInfor($page)
{
	$sql['table']='income';
	$begin=($page-1)*15;
	$sql['limit']=$begin.' 15';
	$data=mysql_select($sql);
	$n=count($data);
	for ($i=0;$i<$n;$i++)
	{
		$id=$data[$i]['sid'];
		$sql=array();
		$sql['table']='student';
		$sql['where']='id';
		$sql['value']=$id;
		$student=mysql_select($sql);
		$data[$i]['name']=$student[0]['name'];
		switch ($data[$i]['type']) 
		{
			case 1:$data[$i]['type']='代收费';break;
			case 2:$data[$i]['type']='保教费';break;
			case 3:$data[$i]['type']='伙食费';break;
			case 4:$data[$i]['type']='其他';break;
			case 0:$data[$i]['type']='报名';break;
		}
	}
	echo json_encode($data);
}

function Oneincome($id)
{
	$sql['table']='income';
	$sql['where']='id';
	$sql['value']=$id;
	$data=mysql_select($sql);
	echo json_encode($data);
}

function outcomeInfor($page)
{
	$sql['table']='outcome';
	$begin=($page-1)*10;
	$sql['limit']=$begin.' 10';
	$data=mysql_select($sql);
	echo json_encode($data);
}

function Oneoutcome($id)
{
	$sql['table']='outcome';
	$sql['where']='id';
	$sql['value']=$id;
	$data=mysql_select($sql);
	echo json_encode($data);
}

function pageInfor($table)
{
	$data=mysql_count($table);
	echo $data[0];
}

function getClass()
{
	$sql['table']='class';
	$data=mysql_select($sql);
	echo json_encode($data);
}

function getFamily($id)
{
	$sql=array();
	$sql['table']='family';
	$sql['where']='id';
	$sql['value']=$id;
	$data=mysql_select($sql);
	echo json_encode($data);
}

function getInfor($id,$table)
{
	$sql=array();
	$sql['table']=$table;
	$sql['where']='id';
	$sql['value']=$id;
	$data=mysql_select($sql);
	echo json_encode($data);
}

function findClass($name)
{
	$sql=array();
	$sql['table']='class';
	$sql['where']='class_name';
	$sql['value']=$name;
	$data=mysql_select($sql);
	echo json_encode($data);
}

function getMoney($id)
{
	$sql=array();
	$sql['table']='money';
	$sql['where']='sid';
	$sql['value']=$id;
	$data=mysql_select($sql);
	echo json_encode($data);
}




function getStandard($id)
{

	$sql=array();
	$sql['table']='standard';
	$sql['where'] = 'id';
	$sql['value'] = 1;
	$data=mysql_select($sql);
	echo json_encode($data);
}

if (isset($_GET['p']))
{
	$do=$_GET['p'];
	switch ($do) 
	{
		case 1:basicInfor();break;//基本信息
		case 2:studentInfor($_GET['page']);break;//分页查看学生基本信息
		case 3:oneStudent($_GET['id']);break;//查看一个学生的基本信息
		case 4:multipleCheck();break;//复选筛选学生信息
		case 5:findOneStudent($_GET['name']);break;//搜索一个学生的名字
		case 6:classInfor($_GET['id']);break;//查询一个班级的信息
		case 7:findStudentInClass($_GET['id']);break;//查询一个班的学生名单
		case 8:incomeInfor($_GET['page']);break;//查询最近的收入
		case 9:Oneincome($_GET['id']);break;//查询某一个收入的详情
		case 10:outcomeInfor($_GET['page']);break;//查询最近的支出
		case 11:Oneoutcome($_GET['id']);break;//查询某一个支出的详情
		case 12:pageInfor($_GET['table']);break;//返回总页数
		case 13:getClass();break;//返回班级名字
		case 14:getFamily($_GET['id']);break;//返回家长信息
		case 15:getInfor($_GET['id'],$_GET['table']);break;//普通查询
		case 16:findClass($_GET['name']);break;//查询是否存在和名字匹配的班级
		case 17:getMoney($_GET['id']);break;//根据学生id找缴费情况
		case 18:getStandard($_GET['id']);break;//获取缴费标准
		//case 19:getHsf($_GET['id']);break;//获取伙食费信息
		//case 20:getStandard($_GET['id']);break;//获取缴费标准
	}
}


?>