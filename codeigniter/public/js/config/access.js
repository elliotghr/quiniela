$(document).ready(function()
{
    load();
    save();
}); 

function save()
{
    $('body').on('click', '#btnSave', function(e)
    {
        $.ajax
        ({
            url: 'access/saveAccess',
            method: 'post',
            data: $('#formEdit').serialize(),
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

                    // $('#dataTable').html(json.dataTable);
                    
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
                $('#btnSave').prop('disabled', false);
            },
        });
    });
}

function load()
{
    $('body').on('click', '#accessAll, #writeAll', function(e)
    {
        $('[data-type="' + $(this).data('type') + '"]').prop('checked', $(this).prop('checked'));
    });

    $('body').on('change', '#rol', function(e)
    {
        if($(this).val().trim() != "")
        {
            $.ajax
            ({
                url: 'access/getAccess',
                method: 'post',
                data: $('#formEdit').serialize(),
                dataType: 'json',
                beforeSend: function ()
                {
                    $('#errorAlert').fadeOut();
                },
                success: function (json)
                {
                    if (json.status == 'OK')
                    {
                        $('#dataTable').html(json.dataTable);
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
        else
        {
            $('#dataTable').html("");
        }
    });

    $('#rol').trigger('change');
}