
function check()
{
	var username=document.getElementById('username');
	var password=document.getElementById('password');
	if (username.value=='')
		alert("请输入用户名");
	else if (password.value=='')
		alert("请填写密码");
	else 
	{
		$.post("controller/login.php",
		{
			username:username.value,
			password:password.value,
			p:0
		}, function(data,status)
		{
      		if (data==1)
      			document.location.href="mini.html";
      		else alert("密码错误！")
		})
	}

}