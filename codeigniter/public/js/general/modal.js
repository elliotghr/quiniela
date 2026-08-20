modal = [];

$(document).ready(function()
{
    function clean()
    {
        $('#modalNew .modal-text, #modalResetPassword .modal-text').val("");
        $('#modalNew .modal-text, #modalResetPassword .modal-text').removeClass('border-danger');
        $('#modalNew .modal-text, #modalResetPassword .modal-text').removeClass('bg-danger');
        $('#modalNew .modal-text, #modalResetPassword .modal-text').removeClass('bg-opacity-10');
    }

    modal.new = function (postUrl)
    {
        $('body').on('click', "[data-action='new']", function (e)
        {
            e.preventDefault();

            clean();

            $('#modalNew').modal('show');
        });

        $('body').on('click', '#modalNew .modal-save', function (e)
        {
            e.preventDefault();

            if(validator.validate('#formNew'))
            {
                $.ajax
                ({
                    url: postUrl,
                    method: 'post',
                    data: $('#formNew').serialize(),
                    dataType: 'json',
                    beforeSend: function ()
                    {
                        $('#modalNew button').prop('disabled', true);
                        $('#errorAlert').fadeOut();
                    },
                    success: function (json)
                    {
                        if (json.status == 'OK')
                        {
                            $('#successMessage').html(json.message);
                            $('#successAlert').fadeIn();
    
                            $('#dataTable').html(json.dataTable);
                            
                            setTimeout(function()
                            {
                                $('#successAlert').fadeOut();
                            }, 3000);
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
                        $('#modalNew').modal('hide');
                        $('#modalNew button').prop('disabled', false);
                    },
                });
            }
        });

    }

    modal.edit = function (postUrl, dataUrl)
    {
        $('body').on('click', "[data-action='edit']", function (e)
        {
            e.preventDefault();

            clean();

            getData(dataUrl, $(this).data('id'));

            $('#modalEdit').modal('show');

        });

        $('body').on('click', '#modalEdit .modal-save', function (e)
        {
            e.preventDefault();

            if(validator.validate('#formEdit'))
            {
                $.ajax
                ({
                    url: postUrl,
                    method: 'post',
                    data: $('#formEdit').serialize(),
                    dataType: 'json',
                    beforeSend: function ()
                    {
                        $('#modalEdit button').prop('disabled', true);
                        $('#errorAlert').fadeOut();
                    },
                    success: function (json)
                    {
                        if (json.status == 'OK')
                        {
                            $('#successMessage').html(json.message);
                            $('#successAlert').fadeIn();

                            $('#dataTable').html(json.dataTable);
                            
                            setTimeout(function()
                            {
                                $('#successAlert').fadeOut();
                            }, 3000);
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
                        $('#modalEdit').modal('hide');
                        $('#modalEdit button').prop('disabled', false);
                    },
                });
            }
        });

        function getData(dataUrl, id)
        {
            $.ajax
            ({
                url: dataUrl,
                method: 'post',
                data: 'id=' + id,
                dataType: 'json',
                beforeSend: function ()
                {
                    $('#errorAlert').fadeOut();
                },
                success: function (json)
                {
                    if (json.status == 'OK')
                    {
                        $('#formEdit').html(json.formEdit);
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
                },
            });
        }
    }

    modal.delete = function (postUrl)
    {
        $('body').on('click', "[data-action='delete']", function (e)
        {
            e.preventDefault();

            $('#modalDelete .modal-text').html('Deseas eliminar el registro: <b>' + $(this).data('value') + '</b>');
            $('#modalDelete #id').val($(this).data('id'));
            $('#modalDelete').modal('show');

        });

        $('body').on('click', '#modalDelete .modal-yes', function (e)
        {
            e.preventDefault();

            $.ajax
            ({
                url: postUrl,
                method: 'post',
                data: $('#formDelete').serialize(),
                dataType: 'json',
                beforeSend: function ()
                {
                    $('#modalDelete button').prop('disabled', true);
                    $('#errorAlert').fadeOut();
                },
                success: function (json)
                {
                    if (json.status == 'OK')
                    {
                        $('#successMessage').html(json.message);
                        $('#successAlert').fadeIn();

                        $('#dataTable').html(json.dataTable);
                        
                        setTimeout(function()
                        {
                            $('#successAlert').fadeOut();
                        }, 3000);
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
                    $('#modalDelete').modal('hide');
                    $('#modalDelete button').prop('disabled', false);
                },
            });
        });
    }

    modal.resetPassword = function (postUrl)
    {
        $('body').on('click', "[data-action='reset']", function (e)
        {
            e.preventDefault();

            clean();

            $('#modalResetPassword').modal('show');
        });

        $('body').on('click', '#modalResetPassword .modal-reset', function (e)
        {
            e.preventDefault();

            if(validator.validate('#formResetPassword'))
            {
                $.ajax
                ({
                    url: postUrl,
                    method: 'post',
                    data: $('#formResetPassword').serialize(),
                    dataType: 'json',
                    beforeSend: function ()
                    {
                        $('#modalResetPassword button').prop('disabled', true);
                        $('#errorAlert').fadeOut();
                    },
                    success: function (json)
                    {
                        if (json.status == 'OK')
                        {
                            $('#successMessage').html(json.message);
                            $('#successAlert').fadeIn();
    
                            console.log(json.url);
                            
                            setTimeout(function()
                            {
                                $('#successAlert').fadeOut();
                            }, 3000);
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
                        $('#modalResetPassword').modal('hide');
                        $('#modalResetPassword button').prop('disabled', false);
                    },
                });
            }
        });

    }
}); 