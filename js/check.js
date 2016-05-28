function checkDate(date)
{
	var reg=new RegExp("[0-9]{4}-[01][0-9]-[0-3][0-9]");
	if (date.search(reg)==-1)
		alert("日期格式不正确！请重新填写");
}