function _eemail_redirect()
{
	window.location = "admin.php?page=general-information";
}

function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}

function _send_email_submit()
{
	if(document.form_eemail.eemail_subject_drop.value=="")
	{
		alert("Please select email subject.")
		return false;
	}
	
	if(confirm("Are you sure you want to send email to all selected email address?"))
	{
		document.form_eemail.submit();
	}
}

function _eemail_help()
{
	window.open("http://www.gopiplus.com/work/2010/09/25/email-newsletter/");
}

function _send_email_testing()
{
	eemail_email_1 = document.getElementById("eemail_email_1");
	eemail_email_2 = document.getElementById("eemail_email_2");
	eemail_email_3 = document.getElementById("eemail_email_3");
	if(document.form_eemail.eemail_subject_drop.value == "")
	{
		alert("Please select email subject.")
		return false;
	}
	else if(document.form_eemail.eemail_email_1.value == "")
	{
		alert("Please enter email address 1.")
		document.form_eemail.eemail_email_1.focus();
        document.form_eemail.eemail_email_1.select();
		return false;
	}
	else if(eemail_email_1.value!="" && (eemail_email_1.value.indexOf("@",0)==-1 || eemail_email_1.value.indexOf(".",0)==-1))
    {
        alert("Please provide a valid email address 1.")
        document.form_eemail.eemail_email_1.focus();
        document.form_eemail.eemail_email_1.select();
        return false;
    }
	else if(eemail_email_2.value!="" && (eemail_email_2.value.indexOf("@",0)==-1 || eemail_email_2.value.indexOf(".",0)==-1))
    {
        alert("Please provide a valid email address 2.")
        document.form_eemail.eemail_email_1.focus();
        document.form_eemail.eemail_email_1.select();
        return false;
    }
	else if(eemail_email_3.value!="" && (eemail_email_3.value.indexOf("@",0)==-1 || eemail_email_3.value.indexOf(".",0)==-1))
    {
        alert("Please provide a valid email address 3.")
        document.form_eemail.eemail_email_1.focus();
        document.form_eemail.eemail_email_1.select();
        return false;
    }
}
