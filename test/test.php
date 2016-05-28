<?php
$rev=$_POST['submit'];
$file=fopen('test.txt', 'w');
fwrite($file, $rev);
fclose($file);
?>