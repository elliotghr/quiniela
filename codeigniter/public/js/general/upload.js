myUpload = [];

jQuery(document).ready(function()
{
    myUpload.setUploader = function (inputFileId, inputFileName, uploadAction, callBack)
    {
        jQuery('body').on('change', inputFileId, function(e)
        {
            var data = new FormData();
            
            jQuery.each(jQuery(inputFileId)[0].files, function(i, file) {
                data.append(inputFileName, file);
            });

            jQuery.ajax
            ({
                url: '/fs/' + uploadAction,
                method: 'post',
                data: data,
                cache: false,
                processData: false,
                contentType: false,
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

                        callBack();
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
    }
});