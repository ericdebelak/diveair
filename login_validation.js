$(document).ready(function()
{
	$("#login").validate(
	{

		rules:
		{
			email:
			{
				required: true,
				email: true,
			}
		},
		

		messages:
		{
			email: " Please enter a valid email",
		}
	});
});