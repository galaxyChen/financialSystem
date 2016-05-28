<?php
include "link.php";

function mysql_select($data)
{
	if (!isset($data['operator']))
		$data['operator']='=';
	$sql='SELECT * FROM '.$data['table'];
	if (isset($data['where']))//处理where附加指令
	{
		$sql=$sql.' WHERE ';
		if (strpos($data['where'], ' '))//多个where的搜索
		{
			$condition=explode(' ',$data['where']);
			$operator=explode(' ', $data['operator']);
			$value=explode(' ', $data['value']);
			$n=count($condition)-1;
			for ($i=0;$i<=$n;$i++)
			{
				if (is_numeric($value[$i]))
				   $sql=$sql.$condition[$i].' '.$operator[$i].' '.$value[$i];
				else $sql=$sql.$condition[$i].' '.$operator[$i].' \''.$value[$i].'\'';
				if ($i!=$n)
					$sql=$sql.' AND ';
			}
		}
		else 
		{
			if (is_numeric($data['value']))
				$sql=$sql.$data['where'].' '.$data['operator'].' '.$data['value'];
			else $sql=$sql.$data['where'].' '.$data['operator'].' \''.$data['value'].'\' ';
		}
	}
	if (isset($data['limit']))
	{
		$limit=explode(' ',$data['limit']);
		$sql=$sql.' LIMIT '.$limit[0].' ,'.$limit[1];
	}
	if (isset($data['order']))
	{
		$sql=$sql.' ORDER BY '.$data['order'];
		if (isset($data['order_d']))
			$sql=$sql.' DESC';
	}
    $result=mysql_query($sql);
    if (!$result) return false;
    $final_result=array();
    while ($final_result[]= mysql_fetch_array($result));
    array_pop($final_result);
    return $final_result;
}

function mysql_count($table)
{
	//计算某一个表的数目
	$sql='SELECT COUNT(*) FROM '.$table;
	$data=mysql_fetch_array(mysql_query($sql));
	return $data;
}

function mysql_insert($data,$table)
{
	$sql='INSERT INTO '.$table.' (';
	$col=array_keys($data);
	foreach ($col as $key => $value) 
		$sql.=$value.',';
	$sql=chop($sql,',');
	$sql.=') VALUES (';
	foreach ($col as $key => $value) 
		if (is_numeric($data[$value]))
			$sql.=$data[$value].',';
		else $sql.='\''.$data[$value].'\',';
	$sql=chop($sql,',');
	$sql.=')';
	mysql_query($sql);
}

function mysql_update($data)
{
	$sql='UPDATE '.$data['table'].' SET ';
	if (strpos($data['col'],' '))
	{
		$col=explode(' ', $data['col']);
		$value=explode(' ', $data['value']);
		$n=count($col);
		for ($i=0;$i<$n;$i++)
			if (is_numeric($value[$i]))
				$sql.=$col[$i].' = '.$value[$i].' ,';
			else $sql.=$col[$i].' = \''.$value[$i].'\' ,';
		$sql=chop($sql,',');
	}
	else if (is_numeric($data['value']))
				$sql.=$data['col'].' = '.$data['value'];
		 else $sql.=$data['col'].' = \''.$data['value'].'\'';
	$sql.=' WHERE '.$data['where'].' = ';
	if (is_numeric($data['case']))
		$sql.=$data['case'];
	else $sql.='\''.$data['case'].'\'' ;
	mysql_query($sql);
}

function mysql_select_joinAll($data)
{
	$sql='SELECT * FROM ';
	$sql.=$data['table1'].','.$data['table2'];
	$sql.=' WHERE ';
	if (!isset($data['operator']))
		$data['operator']='=';
	$case1=$data['table1'].'.'.$data['value1'];
	$case2=$data['table2'].'.'.$data['value2'];
	$sql.=$case1.' = '.$case2;
	if (isset($data['where']))
		$sql.=' AND '.$data['where'].$data['operator'].$data['value'];
	if (isset($data['order']))
	{
		$sql=$sql.' ORDER BY '.$data['order'];
		if (isset($data['order_d']))
			$sql=$sql.' DESC';
	}
	if (isset($data['limit']))
	{
		$limit=explode(' ',$data['limit']);
		$sql=$sql.' LIMIT '.$limit[0].' ,'.$limit[1];
	}

	$result=mysql_query($sql);
	$response=array();
	while ($response[]=mysql_fetch_array($result));
	return $response;
}

function mysql_delete($data)
{
	$sql='DELETE FROM ';
	$sql.=$data['table'];
	$sql.=' WHERE ';
	$sql.=$data['where'];
	$sql.=' = ';
	$sql.=$data['value'];
	mysql_query($sql);
}

?>
