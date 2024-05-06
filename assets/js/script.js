"use strict";

const FILENAME = location.pathname;

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
			USERNAME.style.backgroundColor = "#FF0000";
			console.error("Username is missing!");
		}
		if (PASSWORD.value == "")
		{
			PASSWORD.style.backgroundColor = "#FF0000";
			console.error("Password is missing!");
		}
		if (PASSWORD_CONFIRMATION.value == "")
		{
			PASSWORD_CONFIRMATION.style.backgroundColor = "#FF0000";
			console.error("Password Confirmation is missing!");
		}
		if (EMAIL.value == "")
		{
			EMAIL.style.backgroundColor = "#FF0000";
			console.error("Email is missing!");
		}
		if (PHONE.value == "")
		{
			PHONE.style.backgroundColor = "#FF0000";
			console.error("Phone is missing!");
		}
		
		console.groupEnd();
	});
	
	USERNAME.addEventListener("input", function ()
	{
		this.style.backgroundColor = "#FFFFFF";
	});
	
	PASSWORD.addEventListener("input", function ()
	{
		this.style.backgroundColor = "#FFFFFF";
	});
	
	PASSWORD_CONFIRMATION.addEventListener("input", function ()
	{
		this.style.backgroundColor = "#FFFFFF";
	});
	
	EMAIL.addEventListener("input", function ()
	{
		this.style.backgroundColor = "#FFFFFF";
	});
	
	PHONE.addEventListener("input", function ()
	{
		this.style.backgroundColor = "#FFFFFF";
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
			USERNAME.style.backgroundColor = "#FF0000";
			console.error("Username is missing!");
		}
		if (PASSWORD.value == "")
		{
			PASSWORD.style.backgroundColor = "#FF0000";
			console.error("Password is missing!");
		}
		
		console.groupEnd();
	});
	
	USERNAME.addEventListener("input", function ()
	{
		this.style.backgroundColor = "#FFFFFF";
	});
	
	PASSWORD.addEventListener("input", function ()
	{
		this.style.backgroundColor = "#FFFFFF";
	});
}