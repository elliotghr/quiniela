jQuery(document).ready(function ()
{
    validator.setRequired('#formParticipant');
    agregar();
});

function agregar()
{
    jQuery('body').on('click', '.btnSave', function(e)
    {
        e.preventDefault();
        if(validator.validate('#formParticipant'))
        {
            jQuery(this).prop('disabled', true);
            jQuery.ajax
            ({
                url: '/participant/saveParticipant',
                method: 'post',
                data: jQuery('#formParticipant').serialize(),
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

                        jQuery('#divMain').html(json.next);
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
                    jQuery('.btnSave').prop('disabled', false);
                },
            });
        }
    });
}

