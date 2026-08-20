$(document).ready(function ()
{
    validator.setRequired('#formNew');
    validator.setRequired('#formEdit');
    modal.edit('/config/rols/saveRol', '/config/rols/getRol');
    modal.delete('/config/rols/deleteRol');
    modal.new('/config/rols/newRol');
}); 

/*
function modalNew()
{
    $('body').on('click', "[data-action='new']", function (e)
    {
        e.preventDefault();

        $('#modalNew .modal-text').removeClass('border-danger');
        $('#modalNew .modal-text').removeClass('bg-danger');
        $('#modalNew .modal-text').removeClass('bg-opacity-10');

        $('#modalNew .modal-text').val($(this).data('value'));
        $('#modalNew').modal('show');

    });

    $('body').on('click', '#modalNew .modal-save', function (e)
    {
        e.preventDefault();

        if($('#modalNew .modal-text').val().trim() != "")
        {
            $.ajax
            ({
                url: '/config/rols/newRol',
                method: 'post',
                data: "text=" + $('#modalNew .modal-text').val(),
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

                        $('#rolsTable').html(json.rolsTable);
                        
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
        else
        {
            $('#modalNew .modal-text').addClass('border-danger');
            $('#modalNew .modal-text').addClass('bg-danger');
            $('#modalNew .modal-text').addClass('bg-opacity-10');
        }
    });
}

function modalEdit()
{
    $('body').on('click', "[data-action='edit']", function (e)
    {
        e.preventDefault();

        $('#modalEdit .modal-text').removeClass('border-danger');
        $('#modalEdit .modal-text').removeClass('bg-danger');
        $('#modalEdit .modal-text').removeClass('bg-opacity-10');

        $('#modalEdit .modal-text').val($(this).data('value'));
        $('#modalEdit .modal-hdn').val($(this).data('id'));
        $('#modalEdit').modal('show');

    });

    $('body').on('click', '#modalEdit .modal-save', function (e)
    {
        e.preventDefault();

        if($('#modalEdit .modal-text').val().trim() != "")
        {
            $.ajax
            ({
                url: '/config/rols/saveRol',
                method: 'post',
                data: "id=" + $('#modalEdit .modal-hdn').val() + "&" + "text=" + $('#modalEdit .modal-text').val(),
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

                        $('#rolsTable').html(json.rolsTable);
                        
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
        else
        {
            $('#modalEdit .modal-text').addClass('border-danger');
            $('#modalEdit .modal-text').addClass('bg-danger');
            $('#modalEdit .modal-text').addClass('bg-opacity-10');
        }
    });
}

function modalDelete()
{
    $('body').on('click', "[data-action='delete']", function (e)
    {
        e.preventDefault();

        $('#modalDelete .modal-body').html('Deseas eliminar el rol <b>' + $(this).data('value') + '</b>');
        $('#modalDelete .modal-hdn').val($(this).data('id'));
        $('#modalDelete').modal('show');

    });

    $('body').on('click', '#modalDelete .modal-yes', function (e)
    {
        e.preventDefault();

        $.ajax
        ({
            url: '/config/rols/deleteRol',
            method: 'post',
            data: "id=" + $('#modalDelete .modal-hdn').val(),
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

                    $('#rolsTable').html(json.rolsTable);
                    
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
*/