function findClass()
{

	var name=document.getElementById("class").value;
	var result=true;
	$.get("controller/controller.php?p=16&name="+name,function(data,status)
	{
		data=JSON.parse(data);
		if (data==false)
		{
			alert("找不到该班级！");
			result=false;
		}
	});
	return result;
}

function getMon(date)
{
	date=date.split('-');
	date=date[1];
	if (date[0]=='0') date=date[1];
	return date;
}

function setDate(id,begin,end)
{
	begin=getMon(begin);
	end=getMon(end);
	$(id+"_b option:eq("+begin+")").attr("selected","selected");
	$(id+"_e option:eq("+end+")").attr("selected","selected");
}


function setData(data)
{
	data=JSON.parse(data);
	document.getElementById("sname").value=data['name'];
	if (data['sex']=='male') sex=0; else sex=1;
	$("#sex option:eq("+sex+")").attr("selected","selected");
	document.getElementById("class").value=data['class'];
	document.getElementById("birth").value=data['birth'];
	document.getElementById("fname").value=data['fname'];
	document.getElementById("fphone").value=data['fphone'];
	document.getElementById("mname").value=data['mname'];
	document.getElementById("mphone").value=data['mphone'];
	document.getElementById("bjf_r").value=data['bjfr'];
	document.getElementById("bjf_g").value=data['bjfg'];
	document.getElementById("dsf_r").value=data['dsfr'];
	document.getElementById("dsf_g").value=data['dsfg'];
	document.getElementById("hsf_r").value=data['hsfr'];
	document.getElementById("hsf_g").value=data['hsfg'];
	setDate('#bjf',data['bjfl'],data['bjfn']);
	setDate('#dsf',data['dsfl'],data['dsfn']);
	setDate('#hsf',data['hsfl'],data['hsfn']);
	document.getElementById("note").value=data['note'];
	$("#type option:eq("+data['type']+")").attr("selected","selected");
}



function check()
{
	if (document.getElementById('sname').value=="")
	{
		alert("未填写姓名！");
		return false;
	}
	else if (document.getElementById('sex').value=="") 
	{
		alert("未填写性别！");
		return false;
	}
	else if (document.getElementById('class').value=="") 
	{
		alert("未填写班级！");
		return false;
	}
	else if (!findClass())
	{
		result=false;
	}
	else
	{
		 $("#student").submit();
		 document.location.href="student.html";
	}
}

window.onload=function()
{
	var id=window.location.search;
	id=id.slice(4);
	$.get("controller/controller.php?p=3&id="+id,
		function(data)
		{
			setData(data);
		})
}