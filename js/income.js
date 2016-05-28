function goAdd()
{
	document.location.href="addIncome-0.html";
}

function setPage(data,page)
{
	// if (data>15)
	// 	data=(data-data%15)/15+1;
	// else data=1;
	//data=JSON.parse(data);
		document.getElementById("total").innerHTML=data;
		document.getElementById("showpage").value=page;
		document.getElementById("showpage").name=page;
}

function createData(data)
{
	data=JSON.parse(data);
	var n=data.length;
	var i=0;
	for (i=n-1;i>=0;i--)
	{
	name=data[i]['name'];
	type=data[i]['type'];
	money=data[i]['money'];
	date=data[i]['date'];
	id=data[i]['id'];
	var txt="<td class=\"zero\"></td>"+
		"<td class=\"first\">"+name+"</td>"+
		"<td class=\"second\">"+type+"</td>"+
		"<td class=\"third\">"+money+"</td>"+
		"<td class=\"forth\">"+date+"</td>";
	var tr=$("<tr id="+id+"></tr>");
	tr.appendTo($("#message"));
	document.getElementById(id).innerHTML=txt;
	}
}





function page(p)
{
	var total=document.getElementById("total").innerHTML;
	var now=document.getElementById("showpage").value;
	if (p==0)
		if (now>1)
		{
			$("#detail").empty();
			now--;
			$.get("controller/controller.php?p=8&page="+now,function(data,status)
			{ 
			createData(data); });
			document.getElementById("showpage").value=now;
			document.getElementById("showpage").name=now;
		}
		else alert("已经是第一页");
	if (p==1)
		if (now<total)
		{
			$("#detail").empty();
			now++;
			$.get("controller/controller.php?p=8&page="+now,function(data,status)
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
		$("#detail").empty();
		$.get("controller/controller.php?p=8&page="+target,function(data,status)
		{
			createData(data); 
		});
		document.getElementById("showpage").value=target;
	}
}

window.onload=function()
{
	$.get("controller/controller.php?p=8&page=1",function(data,status)
	{ createData(data); })
	$.get("controller/controller.php?p=12&table=income",function(data,status)
	{
		setPage(data,1);
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
			$.post("controller/delete.php",{data:chosedId});
		})
	})