function submit()
{
	if (document.getElementById("money").value=="")
		alert("请填写收费金额！");
	else 
	{
		$.post("controller/addIncome.php",{
			p:1,
			money:document.getElementById("money").value,
			date:document.getElementById("time").value,
			note:document.getElementById("note").value,
			sid:id
		},function(data,status)
		{
			alert(data);
			document.location.href="income.html";
		});
	}
}

function setData(data,i)
{
	var ifowe=data[i]['dsf_owe'];
	$.get("controller/controller.php?p=17&id="+data[i]['id'],
	function(data,status)
	{//获取学生money表的信息
		data=JSON.parse(data);
		standard=getStandard(data[0]['sid']);
		var owe;
		if (ifowe==0)
			owe="已缴清,上次缴费时间"+data[0]['dsfl'];
		else owe=ifowe;
		document.getElementById("owe").innerHTML=owe;
		var date=getDateMysql();
		document.getElementById('time').value=date;
	})
	
}

function findStudent()
{
	$("#change").remove();
	document.getElementById("owe").innerHTML="";
	var name=document.getElementById('name').value;
	$.get("controller/controller.php?p=5&name="+name+"&t="+Math.random(),function(data,status)
	{
		if (data==0)
		{
			$("#state").css("color","red");
			document.getElementById("state").innerHTML="未找到学生";
		}
		else 
		{
			data=JSON.parse(data);	
			id=data[0]['id'];

			var i=0;
			$.get("controller/controller.php?p=6&id="+data[0]['class'],function(data,status)
			{

				data=JSON.parse(data);
				classn=data[0]['class_name'];
				$("#state").css("color","black");
				document.getElementById("state").innerHTML="班级："+classn;
           
			});
			if (data.length>1)
			{

				var txt=$("<button id='change'>换一个？</button>");
				txt.attr("value",0);
				txt.addClass("change");
				txt.appendTo("#inname");
			}
			setData(data,i);
		}
	})
}