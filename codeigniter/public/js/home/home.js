$(document).ready(function()
{
    jQuery(document).ready(function ()
    {
        jQuery("body").on("click", ".btnSave", function(e)
        {
            e.preventDefault();
            
            jQuery.ajax
            ({
                url: '/quinielas/mis-quinielas/savePronosticos',
                method: 'post',
                data: jQuery('#formQuiniela').serialize(),
                dataType: 'json',
                beforeSend: function ()
                {
                    jQuery('#errorAlert').fadeOut();
                },
                success: function (json)
                {
                    if (json.status == 'OK')
                    {
                        jQuery('#successMessage').html(json.message);
                        jQuery('#successAlert').fadeIn();
                        
                        setTimeout(function()
                        {
                            jQuery('#successAlert').fadeOut();
                        }, 3000);
                    }
                    else
                    {
                        jQuery('#errorMessage').html(json.message);
                        jQuery('#errorAlert').fadeIn();
                    }
                },
                error: function (xhr, status)
                {
                    jQuery('#errorMessage').html('Error en el servidor');
                    jQuery('#errorAlert').fadeIn();
                },
                complete: function (xhr, status)
                {
                    console.log('COMPLETE', status, xhr);
                },
            });
        });
    });
}); 