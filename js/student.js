function goAdd()
{
	document.location.href="addStudent.html";
}

function setPage(data,page)
{
		total=Math.ceil(data/15);
		document.getElementById("total").innerHTML=total;
		document.getElementById("showpage").value=page;
		document.getElementById("showpage").name=page;
}

function goDetail(id)
{
	document.location.href="studentInf.html?id="+id;
}

function createData(data)
{
	var people=JSON.parse(data);
		var n=people.length;
		var i;
		for (i=0;i<n-1;i++)
		{
			var id=people[i]['0'];
			var name=people[i]['name'];
			var pclass=people[i]['class_name'];
			var status=people[i]['owe'];
			if (status>0) status="欠"+status;
			else status="已缴清";
			var text=$('<tr id='+id+'></tr>');
			text.addClass("detail");
			text.appendTo("#s_detail");
			document.getElementById(id).innerHTML=
			"<td class=\"zero\"></td>"+
			"<td class=\"first\" onclick=goDetail("+id+")>"+name+"</td><td class=\"second\"><p class=\'pclass\'>"+pclass+"</p></td>"+
			"<td class=\"third\"><p class=\'pclass\'>"+status+"</p></td><td class=\"forth\">"+
			"<input type=\"checkbox\"  class=\"chose\"></td>";
		}
		
}


function submitStudent(name)
{
	$.get("controller/controller.php?p=5&name="+name,function(data,status)
	{
		if (data!=0)
		{
			$("#s_detail").empty();
			createData(data);
			setPage(JSON.parse(data).length,1);
		}
		else alert("未找到！");
	})
}


function name1()
{
	var name=document.getElementById("s_name").value;
	submitStudent(name);
}


function name2()
{
	if (event.keyCode == 13)
		{
			var name=document.getElementById("s_name").value;
			submitStudent(name);
			
		}
}

function setSearch(data)
{
	var cla=JSON.parse(data);
	var n=cla.length;
	var i;
	for (i=0;i<n;i++)
	{
		var text=$("<option></option>");
		var id="class-"+cla[i]['id']
		text.attr("id",id);
		text.appendTo($("#sclass"));
		document.getElementById(id).innerHTML=cla[i]['class_name'];
	}
}

function page(p)
{
	var total=document.getElementById("total").innerHTML;
	var now=document.getElementById("showpage").value;
	if (p==0)
		if (now>1)
		{
			$("#s_detail").empty();
			now--;
			$.get("controller/controller.php?p=2&page="+now,function(data,status)
			{ 
			createData(data); });
			document.getElementById("showpage").value=now;
			document.getElementById("showpage").name=now;
		}
		else alert("已经是第一页");
	if (p==1)
		if (now<total)
		{
			$("#s_detail").empty();
			now++;
			$.get("controller/controller.php?p=2&page="+now,function(data,status)
			{ 
			createData(data); 
			});
			document.getElementById("showpage").value=now;
			document.getElementById("showpage").name=now;
		}
		else alert("已经是最后一页");
}

function skip(now)
{
	if (event.keyCode == 13)
	{
		var target=document.getElementById("showpage").value;
		var total=document.getElementById("total").innerHTML;
		if (target>total||target<1)
			{
				alert("页码不合法");
				document.getElementById("showpage").value=now;
				return;
			}
		$("#s_detail").empty();
		$.get("controller/controller.php?p=2&page="+target,function(data,status)
		{
			createData(data); 
		});
		document.getElementById("showpage").value=target;
	}
}

window.onload=function()
{
	$.get("controller/controller.php?p=2&page=1",function(data,status)
	{ createData(data); })
	$.get("controller/controller.php?p=13",function(data,status)
	{
		setSearch(data);
	$.get("controller/controller.php?p=12&table=student",function(data,status)
	{
		setPage(data,1);        
	})
})
}



$(document).ready(
	function()
	{
		$('#delete').click(function()
		{
			var chosedId="";
			$(".chose").each(function()
			{
				if ($(this).is(":checked"))
					chosedId+=$(this).parents("tr").attr("id")+" ";
			})
			$.post("controller/delete.php",{data:chosedId},function(data)
				{
					alert("删除成功！");
					document.location.href="student.html";
				});
		})
	})