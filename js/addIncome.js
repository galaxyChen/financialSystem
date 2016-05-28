function goNext()
{
	var type=$("#type option:selected");
	type=type.val();
	switch (type)
	{
		case '0':document.location.href="addIncome-bjf.html";break;
		case '1':document.location.href="addIncome-dsf.html";break;
		case '2':document.location.href="addIncome-hsf.html";break;
		case '3':document.location.href="addIncome-qt.html";break;
	}
}