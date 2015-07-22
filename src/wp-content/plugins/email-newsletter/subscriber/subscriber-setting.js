// JavaScript Document

function _eemail_submit()
{
	if(document.eemail_form.eemail_email_sub.value=="")
	{
		alert("Please enter email address.")
		document.eemail_form.eemail_email_sub.focus();
		return false;
	}
	//else if(document.eemail_form.eemail_name_sub.value=="")
//	{
//		alert("Please enter email name.")
//		document.eemail_form.eemail_name_sub.focus();
//		return false;
//	}
	else if(document.eemail_form.eemail_status_sub.value=="" || document.eemail_form.eemail_status_sub.value=="Select")
	{
		alert("Please select the status.")
		document.eemail_form.eemail_status_sub.focus();
		return false;
	}
}

function _eemail_delete(id,query)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_eemail_display.action="admin.php?page=view-subscriber&search="+query+"&ac=del&did="+id;
		document.frm_eemail_display.submit();
	}
}

function _eemail_resend(id,query)
{
	document.frm_eemail_display.action="admin.php?page=view-subscriber&ac=resend&search="+query+"&did="+id;
	document.frm_eemail_display.submit();
}

function _eemail_redirect()
{
	window.location = "admin.php?page=view-subscriber";
}

function _eemail_help()
{
	window.open("http://www.gopiplus.com/work/2010/09/25/email-newsletter/");
}

function _eemail_import()
{
	if(document.form_importemails.importemails.value=="")
	{
		alert("Please enter the email address.")
		document.form_importemails.importemails.focus();
		return false;
	}
	
	entry = document.form_importemails.importemails.value;
	var last = entry.charAt(entry.length-1); 
	if (last == ',') 
	{
		alert("Comma not allowed at the end."); 
		document.form_importemails.importemails.focus();
		return false;
	}
	
	var tarr = entry.split(',');
	var str = '';
	var j = 1;
	for (var i=0; i<tarr.length; i++) 
	{
		if (tarr[i] == '') 
		{ 
			str += 'Empty value on the position '+j+'\n'; 
			j = j+1;
		} 
		else
		{
			j = j+1;
		}
	}
	
	if (str != '') 
	{ 
		alert(str); 
		document.form_importemails.importemails.focus();
		return false;
	}
	
	if(j > 25)
	{
		alert("Maximum 25 emails only allowed at one time."); 
		document.form_importemails.importemails.focus();
		return false;
	}
	
}

function _subscribermultipledelete()
{
	if(document.frm_eemail_display.action.value=="")
	{
		alert("Please select the bulk option."); 
		document.frm_eemail_display.action.focus();
		return false;
	}
	
	if(document.frm_eemail_display.action.value == "delete")
	{
		if(confirm("Do you want to delete selected record(s)?"))
		{
			if(confirm("Are you sure you want to delete?"))
			{
				var searchquery = document.frm_eemail_display.searchquery.value;
				document.frm_eemail_display.frm_eemail_bulkaction.value = 'delete';
				document.frm_eemail_display.action="admin.php?page=view-subscriber&bulkaction=delete&search=" + searchquery;
				document.frm_eemail_display.submit();
			}
		}
	}
	else if(document.frm_eemail_display.action.value == "resend")
	{
		if(confirm("Do you want to resend confirmation email? \nAlso please note, this will update subscriber current status to 'Not confirmed'."))
		{
			var searchquery = document.frm_eemail_display.searchquery.value;
			document.frm_eemail_display.frm_eemail_bulkaction.value = 'resend';
			document.frm_eemail_display.action="admin.php?page=view-subscriber&bulkaction=resend&search=" + searchquery;
			document.frm_eemail_display.submit();
		}
	}
}