$(document).ready(function()
{
	$("#register").validate(
	{

		rules:
		{
			email:
			{
				required: true,
				email: true,
			},
			
			firstName:
			{
				required: true,
				pattern: /^[A-Za-z\'\s\-]+$/,
			},
			
			lastName:
			{
				required: true,
				pattern: /^[A-Za-z\'\s\-]+$/,
			},
			
			confirmPassword:
			{
				equalTo:"#password",
			}
			
		},
		

		messages:
		{
			email: " Please enter a valid email",
			firstName: " Please enter a first name using only letters, spaces, hypens and apostrophes",
			lastName: " Please enter a first name using only letters, spaces, hypens and apostrophes",
			confirmPassword: " Passwords do not match"
		}
	});
});