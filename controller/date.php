<?php
function getDateNow()
{
	$date=getdate();
	$now=$date['year'].'-'.$date['mon'].'-'.$date['mday'];
	return $now;
}


function setNext($begin,$next,$type)
{
	$date=getdate();
	$year=$date['year'];
	if ($begin<$next)
		$mon=$next;
	else {
		$mon=$next;
		$year++;
	}
	switch ($type) {
		case 1:
			if ($mon<9&&$mon>=2)
				$mon=9;
			else if ($mon>=9||$mon<2)
				$mon=2;
			break;
		case 2:
			$mon++;
			if ($mon>12)
			{
				$mon-=12;
				$year++;
			}
			break;
	}
	$next=$year.'-'.$mon.'-01';
	return $next;
}


?>