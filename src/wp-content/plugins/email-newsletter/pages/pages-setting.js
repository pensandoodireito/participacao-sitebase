function _eemail_redirect()
{
	window.location = "admin.php?page=general-information";
}

function _eemail_help()
{
	window.open("http://www.gopiplus.com/work/2010/09/25/email-newsletter/");
}

function _email_setting()
{
	if(document.eemail_form.eemail_admin_email_option.value=="")
	{
		alert("Please select admin email option (Send auto email to admin).")
		document.eemail_form.eemail_admin_email_option.focus();
		return false;
	}
	else if(document.eemail_form.eemail_user_email_option.value == "")
	{
		alert("Please select user email option (Send auto email to subscriber).")
		document.eemail_form.eemail_user_email_option.focus();
		return false;
	}
}