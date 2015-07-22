// JavaScript Document

function _eemail_submit()
{
	if(document.eemail_form.eemail_subject.value=="")
	{
		alert("Please enter the email subject.")
		document.eemail_form.eemail_subject.focus();
		return false;
	}
	else if(document.eemail_form.eemail_content.value=="")
	{
		alert("Please enter the email content.")
		return false;
	}
	else if(document.eemail_form.eemail_status.value=="" || document.eemail_form.eemail_status.value=="Select")
	{
		alert("Please select the display status.")
		document.eemail_form.eemail_status.focus();
		return false;
	}
}

function _eemail_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_eemail_display.action="admin.php?page=compose-email&ac=del&did="+id;
		document.frm_eemail_display.submit();
	}
}

function _eemail_redirect()
{
	window.location = "admin.php?page=compose-email";
}

function _eemail_help()
{
	window.open("http://www.gopiplus.com/work/2010/09/25/email-newsletter/");
}