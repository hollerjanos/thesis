"use strict";

const FILENAME = location.pathname;

const COLOR_NORMAL = "#FFFFFF";
const COLOR_MISSING = "#FFBFBF";

if (FILENAME == "/register.php")
{
	const USERNAME = document.getElementById("username");
	const PASSWORD = document.getElementById("password");
	const PASSWORD_CONFIRMATION = document.getElementById("passwordConfirmation");
	const EMAIL = document.getElementById("email");
	const PHONE = document.getElementById("phone");
	
	const SUBMIT = document.getElementById("submit");
	
	SUBMIT.addEventListener("click", function()
	{
		console.groupCollapsed("You tried to submit the FORM.");
		
		if (USERNAME.value == "")
		{
			USERNAME.style.backgroundColor = COLOR_MISSING;
			console.error("Username is missing!");
		}
		if (PASSWORD.value == "")
		{
			PASSWORD.style.backgroundColor = COLOR_MISSING;
			console.error("Password is missing!");
		}
		if (PASSWORD_CONFIRMATION.value == "")
		{
			PASSWORD_CONFIRMATION.style.backgroundColor = COLOR_MISSING;
			console.error("Password Confirmation is missing!");
		}
		if (EMAIL.value == "")
		{
			EMAIL.style.backgroundColor = COLOR_MISSING;
			console.error("Email is missing!");
		}
		if (PHONE.value == "")
		{
			PHONE.style.backgroundColor = COLOR_MISSING;
			console.error("Phone is missing!");
		}
		
		console.groupEnd();
	});
	
	USERNAME.addEventListener("input", function ()
	{
		this.style.backgroundColor = COLOR_NORMAL;
	});
	
	PASSWORD.addEventListener("input", function ()
	{
		this.style.backgroundColor = COLOR_NORMAL;
	});
	
	PASSWORD_CONFIRMATION.addEventListener("input", function ()
	{
		this.style.backgroundColor = COLOR_NORMAL;
	});
	
	EMAIL.addEventListener("input", function ()
	{
		this.style.backgroundColor = COLOR_NORMAL;
	});
	
	PHONE.addEventListener("input", function ()
	{
		this.style.backgroundColor = COLOR_NORMAL;
	});
	
}
else if (FILENAME == "/login.php")
{
	const USERNAME = document.getElementById("username");
	const PASSWORD = document.getElementById("password");
	
	const SUBMIT = document.getElementById("submit");
	
	SUBMIT.addEventListener("click", function()
	{
		console.groupCollapsed("You tried to submit the FORM.");
		
		if (USERNAME.value == "")
		{
			USERNAME.style.backgroundColor = COLOR_MISSING;
			console.error("Username is missing!");
		}
		if (PASSWORD.value == "")
		{
			PASSWORD.style.backgroundColor = COLOR_MISSING;
			console.error("Password is missing!");
		}
		
		console.groupEnd();
	});
	
	USERNAME.addEventListener("input", function ()
	{
		this.style.backgroundColor = COLOR_NORMAL;
	});
	
	PASSWORD.addEventListener("input", function ()
	{
		this.style.backgroundColor = COLOR_NORMAL;
	});
}