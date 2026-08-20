$(document).ready(function()
{
    $('body').on('click', '#menuLogout', function(e)
    {
        e.preventDefault();
        
        window.location.replace("/logout");
    });
}); 