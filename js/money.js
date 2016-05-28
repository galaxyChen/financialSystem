


function getDayoff(id)
{
	return 0;
}


function getStandard(id)
{
	money=new Array();
	standard=new Array();
	$.ajax({
		url:"controller/controller.php?p=18&id="+id,
		async:false,
		type:"GET",
		success:function(data)
		{
			data=JSON.parse(data);
			standard=data[0];
		}
	});
	$.ajax({
		url:"controller/controller.php?p=3&id="+id,
		async:false,
		type:"GET",
		success:function(data)
		{
			data=JSON.parse(data);
			data=data[0];
			if (data['first']==0)
				money=standard;
			else $.ajax({
					url:"controller/controller.php?p=17&id="+id,
					async:false,
					type:"GET",
					success:function(data)
						{
							data=JSON.parse(data);
							data=data[0];
							if (data['dsfr']>data['dsfg'])
								money['dsf']=data['dsfr']; else money['dsf']=standard['dsf'];
							if (data['bjfr']>data['bjfg'])
								money['bjf']=data['bjfr']; else money['bjf']=standard['bjf'];
							if (data['hsfr']>data['hsfg'])
								money['hsf']=data['hsfr']; else money['hsf']=standard['hsf'];
							money['hsfs']=standard['hsfs'];
						}
					});
		}
	})
	
	return money;
}

function biggerDate(date)//date小于现在的时间返回true，否则返回false
{
	date=date.split('-');
	var now=new Date();
	var target=new Date();
	target.setDate(date[0],date[1],date[2]);
	if (now.getFullYear()>target.getFullYear())
		return true;
	else if (now.getFullYear()==target.getFullYear()&&now.getMonth()>target.getMonth())
		return true;
	else return false;
}

function getDateMysql()
{
	var date=new Date();
	var year=date.getFullYear();
	var mon=1+date.getMonth();
	var day=date.getDate();
	var now=year+'-'+mon+'-'+day;
	return now;
}
