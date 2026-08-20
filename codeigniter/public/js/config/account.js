$(document).ready(function () {
    
    upload();

    $('body').on('keypress', "input[name='newPasswordBis']", function (e)
    {
        if (e.keyCode == 13)
        {
            $('#btnSave').click();
            e.preventDefault();
        }
    });

    $('body').on('click', "#btnSave", function (e)
    {
        e.preventDefault();

        if (validator.validate('#formData'))
        {
            $.ajax
            ({
                url: '/config/account/savePassword',
                method: 'post',
                data: $('#formData').serialize(),
                dataType: 'json',
                beforeSend: function ()
                {
                    $('#btnSave').prop('disabled', true);
                    $('#errorAlert').fadeOut();
                },
                success: function (json)
                {
                    if (json.status == 'OK')
                    {
                        $('#successMessage').html(json.message);
                        $('#successAlert').fadeIn();
                        
                        setTimeout(function()
                        {
                            $('#successAlert').fadeOut();
                        }, 3000);

                        if(json.changePassword === '1')
                        {
                            window.location.href = '/home';
                        }
                    }
                    else
                    {
                        $('#errorMessage').html(json.message);
                        $('#errorAlert').fadeIn();
                    }
                },
                error: function (xhr, status)
                {
                    $('#errorMessage').html('Error en el servidor');
                    $('#errorAlert').fadeIn();
                },
                complete: function (xhr, status)
                {
                    console.log('COMPLETE', status, xhr);
                    $('#btnSave').prop('disabled', false);
                },
            });
        }
    });
}); 

function upload()
{
    myUpload.setUploader('#fileAvatar', 'fileAvatar', 'uploadAvatar', afterUpload);
}

function afterUpload()
{
    window.location.href = '/config/account';
}