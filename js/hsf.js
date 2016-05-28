function checkMoneyState()
{
	var state=document.getElementById("owe");
	if (state.innerHTML=="未缴清")
		if (confirm("当前学生实际缴费不等于未缴费，是否继续？"))
		{
			if (confirm("是否将学生状态设置为已缴清？"))
				state.innerHTML="已缴清";
		}
		else return false;

	return true;
}


function checkMoney(money)
{
	var state=document.getElementById("owe");
	var need=document.getElementById("require").innerHTML;
	need=parseInt(need);
	if (money==need)
		state.innerHTML="已缴清";
	else state.innerHTML="未缴清";
}


function count()
{
	var id=document.getElementById("id").innerHTML;
	id=id.slice(3);
	id=parseInt(id);
	var dayoff=document.getElementById("dayoff").value;
	$.get("controller/controller.php?p=17&id="+id,
		function(data,status)
		{
			data=JSON.parse(data);
			data=data[0];

			var timeb=document.getElementById("timeb").value;
			var timee=document.getElementById("timee").value;
			var month=timee-timeb;
			if (month<0) month+=12;
			if (month==0&&data['hsfg']<data['hsfr']) month++;
			standard=getStandard(id);
			document.getElementById('should').innerHTML=standard['hsf']*month;
			document.getElementById("back").innerHTML=standard['hsfs']*dayoff;
			var back=document.getElementById("back").innerHTML;
			document.getElementById("require").innerHTML=document.getElementById("should").innerHTML-back;	
		});
}


function submit()
{
	var id=document.getElementById("id").innerHTML;
	id=id.slice(3);
	if (document.getElementById("money").value=="")
		alert("请填写收费金额！");
	else 
	{
		id=parseInt(id);
		var Timeb=document.getElementById("timeb").value;
		if (Timeb[0]=='0') Timeb=Timeb.slice(0);
		Timeb=parseInt(Timeb);
		var Timee=document.getElementById("timee").value;
		if (Timee[0]=='0') Timee=Timee.slice(0);
		Timee=parseInt(Timee);
		var Money=document.getElementById("money").value;
		Money=parseInt(Money);
		var Back=document.getElementById("back").innerHTML;
		Back=parseInt(Back);
		var ifowe=document.getElementById("owe").innerHTML;
		$.post("controller/addIncome.php",{
			p:3,
			money:Money,
			require:document.getElementById("require").innerHTML,
			timeb:Timeb,
			timee:Timee,
			back:Back,
			date:document.getElementById("time").value,
			sid:id,
		},function(data,status)
		{
			alert(data);
			document.location.href="income.html";
		});
	}
}

function setData(data,i)//data[i]是当前的学生信息，来自student表
{
	var student=data[i];
	$.get("controller/controller.php?p=17&id="+student['id'],
		function(data)//data来自money表
		{
			data=JSON.parse(data);
			data=data[0];
			document.getElementById("ltime").innerHTML=data['hsfl'];
			document.getElementById('time').value=getDateMysql();
			lastTime=data['hsfl'].split('-');
			lastTime=lastTime[1];
			if (lastTime[0]=='0') lastTime=lastTime[1];
			lastTime=parseInt(lastTime);
			document.getElementById("timeb").value=lastTime;
			var time=new Date();
			next=time.getMonth()+1;
			document.getElementById("timee").value=next;
			if (student['hsf_owe']==0) owe="已缴清";else owe=student['hsf_owe'];
			if (student['hsf_owe']>0) 
			{
				$("#timee").attr("readonly",next);
				$("#timeb").attr("readonly",next);
			}
			document.getElementById("should").innerHTML=owe;
			document.getElementById("dayoff").value=getDayoff();
			document.getElementById("require").innerHTML=owe;

		})
}


function findStudent()
{
	$("#change").remove();
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
				document.getElementById("id").innerHTML="id:"+id;
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