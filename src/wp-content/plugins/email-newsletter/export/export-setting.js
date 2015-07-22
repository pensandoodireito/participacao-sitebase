function exportcsv(url, option)
{
	if(confirm("Do you want to export the emails?"))
	{
		document.frm_emailnewsletter.action= url+"?option="+option;
		document.frm_emailnewsletter.submit();
	}
}