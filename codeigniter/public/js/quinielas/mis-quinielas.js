var saved = true;

jQuery(document).ready(function ()
{
    viewQuiniela();
    buttons();
    validadores();
    tabs();
    mainTabs();
    tabAdminActions();
    tabPorPartidoActions();
    save();
});

function save()
{
    jQuery(document).ajaxSend(function(event, xhr, options)
    {
        saved = true;
        if(options.url.includes('savePronosticos'))
        {
            jQuery('#alertSave').fadeOut();
        }
        else
        {
            jQuery('#alertSave').hide();
        }
    });
}

function tabs()
{
    jQuery('body').on('click', '[data-qtabs]', function()
    {
        jQuery('[data-qtabs]').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('form[data-qform]').hide();
        jQuery('form[data-qform=' + jQuery(this).data('qtabs') + ']').show();

        switch(jQuery(this).data('qtabs'))
        {
            case 'quiniela':
                tabQuiniela();
                break;
            case 'globales':
                tabGlobales();
                break;
            case 'porPartido':
                tabPorPartido();
                break;
            case 'admin':
                tabAdmin();
                break;
        }
    });

    function tabQuiniela()
    {
        jQuery('#pronosticoId').trigger('change');
    }

    function tabGlobales()
    {
        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/getGlobal',
            method: 'post',
            data: jQuery('#formGlobales').serialize(),
            dataType: 'json',
            beforeSend: function ()
            {
                jQuery('#errorAlert').fadeOut();
            },
            success: function (json)
            {
                if (json.status == 'OK')
                {
                    jQuery('#divGlobales').html(json.dataTable);
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
    }

    function tabPorPartido()
    {
        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/getPorPartido',
            method: 'post',
            data: jQuery('#formPartidos').serialize(),
            dataType: 'json',
            beforeSend: function ()
            {
                jQuery('#errorAlert').fadeOut();
            },
            success: function (json)
            {
                if (json.status == 'OK')
                {
                    jQuery('#divPorPartido').html(json.dataTable);
                    jQuery('#partidoId').trigger('change');
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
    }

    function tabAdmin()
    {
        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/getAdmin',
            method: 'post',
            data: jQuery('#formAdmin').serialize(),
            dataType: 'json',
            beforeSend: function ()
            {
                jQuery('#errorAlert').fadeOut();
            },
            success: function (json)
            {
                if (json.status == 'OK')
                {
                    jQuery('#divAdmin').html(json.dataTable);
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
    }
}

function tabAdminActions()
{
    jQuery('body').on('click', '.admCheck', function()
    {
        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/savePronostico',
            method: 'post',
            data: jQuery('#formAdmin').serialize() + '&pid=' + jQuery(this).val(),
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
}

function tabPorPartidoActions()
{
    jQuery('body').on('change', '#partidoId', function()
    {
        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/getResultadosPorPartido',
            method: 'post',
            data: jQuery('#formPartidos').serialize(),
            dataType: 'json',
            beforeSend: function ()
            {
                jQuery('#errorAlert').fadeOut();
            },
            success: function (json)
            {
                if (json.status == 'OK')
                {
                    jQuery('#divResultadosPorPartido').html(json.dataTable);
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

function validadores()
{
    jQuery('body').on('focusout', '[name^=partido]', function(e)
    {
        e.preventDefault();

        selector = '#formQuiniela input[name="' + jQuery(this).attr('name') + '"][data-validate]';
        validator.validateSelector(selector);
    });

    jQuery('body').on('change', 'input[name^=partido]', function(e)
    {
        e.preventDefault();

        saved = false;
        jQuery('#alertSave').fadeIn();
    });
}

function mainTabs()
{
    jQuery('body').on('click', '[data-main-tabs]', function(e)
    {
        e.preventDefault();

        jQuery('[data-main-tabs]').removeClass('active');
        jQuery(this).addClass('active');

        var tab = jQuery(this).data('main-tabs');
        jQuery('#divMisQuinielas, #divNuevaQuiniela').hide();

        if (tab === 'misQuinielas')
        {
            jQuery('#divMisQuinielas').show();
        }
        else if (tab === 'nuevaQuiniela')
        {
            resetFormQuiniela();
            jQuery('#divNuevaQuiniela').show();
        }
    });

    function resetFormQuiniela()
    {
        jQuery('#quinielaId').val('');
        jQuery('#formNuevaQuiniela')[0].reset();
        jQuery('#temporada').val(new Date().getFullYear());
        jQuery('#maxPronosticos').val(1);
        jQuery('#tituloFormQuiniela').html('<i class="fas fa-plus me-2"></i>Nueva Quiniela');
        jQuery('#btnSubmitTexto').text('Crear Quiniela');
        jQuery('.btnResetFormQuiniela').hide();
        jQuery('.btnEliminarQuiniela').hide();
        jQuery('#ligaId, #temporada').prop('disabled', false);
    }
}

function buttons()
{
    jQuery('body').on('click', '.btnRegresar', function(e)
    {
        e.preventDefault();

        window.location.href = '/quinielas/mis-quinielas';
    });

    jQuery('body').on('click', '[data-action="editQuiniela"]', function(e)
    {
        e.preventDefault();
        e.stopPropagation();

        var encodedId = jQuery(this).data('id');

        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/getQuinielaData',
            method: 'post',
            data: 'qid=' + encodedId,
            dataType: 'json',
            beforeSend: function ()
            {
                jQuery('#errorAlert').fadeOut();
            },
            success: function (json)
            {
                if (json.status == 'OK')
                {
                    jQuery('#quinielaId').val(encodedId);
                    jQuery('#ligaId').val(json.quiniela.liga).prop('disabled', true);
                    jQuery('#temporada').val(json.quiniela.temporada).prop('disabled', true);
                    jQuery('#nombre').val(json.quiniela.nombre);
                    jQuery('#fechaInicio').val(json.quiniela.fecha_inicio);
                    jQuery('#rondas').val(json.quiniela.rondas);
                    jQuery('#maxPronosticos').val(json.quiniela.max_pronosticos);
                    jQuery('#tituloFormQuiniela').html('<i class="fas fa-edit me-2"></i>Editar Quiniela');
                    jQuery('#btnSubmitTexto').text('Guardar Cambios');
                    jQuery('.btnResetFormQuiniela').show();
                    jQuery('.btnEliminarQuiniela').show();

                    // Cambiar pestaña manualmente para no triggear resetFormQuiniela()
                    jQuery('[data-main-tabs]').removeClass('active');
                    jQuery('[data-main-tabs="nuevaQuiniela"]').addClass('active');
                    jQuery('#divMisQuinielas, #divNuevaQuiniela').hide();
                    jQuery('#divNuevaQuiniela').show();
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
        });
    });

    jQuery('body').on('click', '.btnResetFormQuiniela', function(e)
    {
        e.preventDefault();
        jQuery('[data-main-tabs="nuevaQuiniela"]').trigger('click');
    });

    jQuery('body').on('click', '.btnEliminarQuiniela', function(e)
    {
        e.preventDefault();

        if (!confirm('¿Estás seguro de que deseas eliminar esta quiniela? Esta acción no se puede deshacer.'))
        {
            return;
        }

        var quinielaId = jQuery('#quinielaId').val();

        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/deleteQuiniela',
            method: 'post',
            data: { quinielaId: quinielaId },
            dataType: 'json',
            beforeSend: function ()
            {
                jQuery('#errorAlert').fadeOut();
                jQuery('.btnEliminarQuiniela').prop('disabled', true);
            },
            success: function (json)
            {
                if (json.status == 'OK')
                {
                    jQuery('#successMessage').html('Quiniela eliminada con éxito');
                    jQuery('#successAlert').fadeIn();

                    setTimeout(function()
                    {
                        jQuery('#successAlert').fadeOut();
                        window.location.reload();
                    }, 2000);
                }
                else
                {
                    jQuery('#errorMessage').html(json.message);
                    jQuery('#errorAlert').fadeIn();
                }
            },
            error: function ()
            {
                jQuery('#errorMessage').html('Error en el servidor');
                jQuery('#errorAlert').fadeIn();
            },
            complete: function ()
            {
                jQuery('.btnEliminarQuiniela').prop('disabled', false);
            },
        });
    });

    jQuery('body').on('click', '.btnNuevaQuiniela', function(e)
    {
        e.preventDefault();

        if (validator.validate('#formNuevaQuiniela'))
        {
            var quinielaId = jQuery('#quinielaId').val();
            var url = quinielaId ? '/quinielas/mis-quinielas/updateQuiniela' : '/quinielas/mis-quinielas/saveQuiniela';
            var mensajeExito = quinielaId ? 'Quiniela actualizada con éxito' : 'Quiniela creada con éxito';

            jQuery.ajax
            ({
                url: url,
                method: 'post',
                data: jQuery('#formNuevaQuiniela').serialize(),
                dataType: 'json',
                beforeSend: function ()
                {
                    jQuery('#errorAlert').fadeOut();
                    jQuery('.btnNuevaQuiniela').prop('disabled', true);
                },
                success: function (json)
                {
                    if (json.status == 'OK')
                    {
                        jQuery('#successMessage').html(mensajeExito);
                        jQuery('#successAlert').fadeIn();

                        setTimeout(function()
                        {
                            jQuery('#successAlert').fadeOut();
                            jQuery('[data-main-tabs="misQuinielas"]').trigger('click');
                            window.location.reload();
                        }, 2000);
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
                    jQuery('.btnNuevaQuiniela').prop('disabled', false);
                },
            });
        }
    });

    jQuery("body").on("click", ".btnSave", function(e)
    {
        e.preventDefault();
        
        if(validator.validate('#formQuiniela'))
        {
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
        }
    });
}

function viewQuiniela()
{
    jQuery("body").on("click", "[data-action='viewQuiniela']", function(e)
    {
        e.preventDefault();
        
        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/getPronosticos',
            method: 'post',
            data: "qid=" + jQuery(this).data('id'),
            dataType: 'json',
            beforeSend: function ()
            {
                jQuery('#errorAlert').fadeOut();
            },
            success: function (json)
            {
                if (json.status == 'OK')
                {
                    jQuery('#divMain').html(json.dataTable);
                    jQuery('[data-qtabs=quiniela]').trigger('click');
                    jQuery('#pronosticoId').trigger('change');
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

    jQuery("body").on("change", "#pronosticoId", function(e)
    {
        e.preventDefault();
        
        jQuery.ajax
        ({
            url: '/quinielas/mis-quinielas/getPartidos',
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
                    jQuery('#divPartidos').html(json.dataTable);
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
