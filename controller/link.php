<?php
$linkcon=mysql_connect("localhost","root","") or die ("数据库链接失败");
mysql_select_db("kgfs",$linkcon);
mysql_query("set names 'utf8'");
?>