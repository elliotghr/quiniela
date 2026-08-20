jQuery(document).ajaxStart(function()
{
    jQuery('#loading').show();
});

jQuery(document).ajaxSend(function()
{
    jQuery('#loading').show();
});

jQuery(document).ajaxStop(function()
{
    jQuery('#loading').hide();
});

jQuery(document).ajaxError(function()
{
    jQuery('#loading').hide();
});

jQuery(document).ajaxComplete(function()
{
    jQuery('#loading').hide();
});

jQuery(document).ajaxSuccess(function()
{
    jQuery('#loading').hide();
});
