$(document).ready(function()
{
    validator.setRequired('#formResetPassword');
    validator.setRequired('#formNew');
    modal.resetPassword('/login/resetPassword');
    modal.new('/login/newUser');

    $('body').on('keypress', '#password', function(e)
    {
        if(e.keyCode == 13)
        {
            $('#btnLogin').click();
            e.preventDefault();
        }
    });

    $('body').on('click', '#errorAlert', function(e)
    {
        e.preventDefault();

        $('#errorAlert').fadeOut();
    });

    $('body').on('click', '#btnLogin', function(e)
    {
        e.preventDefault();
        
        if(jQuery("#captchaLogin_v3")[0] != undefined)
        {
            grecaptcha.ready(function()
            {
                action = "login";
                grecaptcha.execute(jQuery("#captchaLogin_v3").data('sitekey'), {action: action}).then(function(token)
                {
                    login(token, action);
                });
            });
      
        }
        else
        {
            login("", "");
        }
    });

    function login(token, action)
    {
        $.ajax
        ({
            url: '/login/auth',
            method: 'post',
            data: $('#formData').serialize() + "&token=" + token + "&action=" + action,
            dataType: 'json',
            beforeSend: function()
            {
                $('#btnLogin').prop('disabled', true);
            },
            success: function(json)
            {
                if(json.status == 'OK')
                {
                    location.reload();
                }
                else
                {
                    $('#btnLogin').prop('disabled', false);
                    $('#errorMessage').html(json.message);
                    $('#errorAlert').fadeIn();

                    try
                    {
                        captchaId = '#captchaLogin';
                        grecaptcha.reset(jQuery(captchaId)[0], { sitekey : jQuery(captchaId).data('sitekey') });
                    }
                    catch(exception)
                    {
                        console.log(exception);
                    }
                }
            },
            error : function(xhr, status)
            {
                console.log('JS ERROR', xhr);
                $('#btnLogin').prop('disabled', false);
            }
        });
    }
});