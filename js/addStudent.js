function setNames(name)
{
	var father=document.getElementById("fname");
	var mother=document.getElementById("mname");
	father.value=name+"爸爸";
	mother.value=name+"妈妈";
}

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
	else $("#student").submit();
}

window.onload=function()
{
	var date=new Date();
	var mon=date.getMonth();
	mon++;
	$("#bjf_b option:eq("+mon+")").attr("selected","selected");
	$("#dsf_b option:eq("+mon+")").attr("selected","selected");
	$("#hsf_b option:eq("+mon+")").attr("selected","selected");
	mon++;
	if (mon>12) mon-=12;
	$("#hsf_e option:eq("+mon+")").attr("selected","selected");
	mon=date.getMonth();
	mon++;
	if (mon>=9||mon<2) mon=1;
	else if (mon<9&&mon>=2) mon=8;
	$("#bjf_e option:eq("+mon+")").attr("selected","selected");
	$("#dsf_e option:eq("+mon+")").attr("selected","selected");
}