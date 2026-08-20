validator = [];

$(document).ready(function()
{
    error = [];

    $('body').on('validate', "[data-validate~='number']", function(e)
    {
        val1 = $(this).val();
        var emailRegex = new RegExp("^[0-9]+$|^$"); // Valida números enteros o vacío
        
        if(!emailRegex.test(val1))
        {
            setError($(this), 'number');
        }
        else
        {
            unsetError($(this), 'number');
        }
    });

    $('body').on('validate', "[data-validate~='date']", function(e)
    {
        badInput = $(this)[0].validity.badInput;
        valid = $(this)[0].validity.valid;

        if(badInput || !valid)
        {
            setError($(this), 'date');
        }
        else
        {
            unsetError($(this), 'date');
        }
    });

    $('body').on('validate', "[data-validate~='email']", function(e)
    {
        val1 = $(this).val();
        var emailRegex = new RegExp("^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$|^$"); // Valida correo correcto o vacío
        
        if(!emailRegex.test(val1))
        {
            setError($(this), 'email');
        }
        else
        {
            unsetError($(this), 'email');
        }
    });

    $('body').on('validate', "[data-validate~='required']", function(e)
    {
        val1 = $(this).val().trim(); // Valida que el campo tenga un caracter por lo menos

        if(val1 === '')
        {
            setError($(this), 'required');
        }
        else
        {
            unsetError($(this), 'required');
        }
    });

    $('body').on('validate', "[data-validate~='password']", function(e)
    {
        val1 = $(this).val();
        var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*\+\.])(?=.{8,})"); // Valida password correcto
        
        if(!strongRegex.test(val1))
        {
            setError($(this), 'password');
        }
        else
        {
            unsetError($(this), 'password');
        }
    });

    $('body').on('validate', "[data-validate~='equal']", function(e)
    {
        val1 = $(this).val().trim();
        val2 = $('[name="' + $(this).data('validate-equal') + '"]').val().trim(); // Valida que dos campos coincidan

        if(val1 != val2)
        {
            setError($(this), 'equal');
        }
        else
        {
            unsetError($(this), 'equal');
        }
    });

    $('body').on('click', '#errorBtn', function(e)
    {
        e.preventDefault();
        $('#errorAlert').fadeOut();
    });

    $('body').on('click', '#successBtn', function(e)
    {
        e.preventDefault();
        $('#successAlert').fadeOut();
    });

    function setError(obj, label)
    {
        if(obj.data('validate').indexOf("orEmpty") === -1)
        {
            setLabelError(obj, label);
            showError();
        }
    }

    function unsetError(obj, label)
    {
        obj.removeClass('border-danger');
        obj.removeClass('bg-danger');
        obj.removeClass('bg-opacity-10');
        unsetLabelError(obj, label);
        showError();
    }

    function setLabelError(obj, label)
    {
        switch(label)
        {
            case 'number':
                error[obj.attr('name') + '|' + label] = 'El campo <b>' + obj.data('validate-label') + '</b> debe de ser numérico';
                break;
                
            case 'required':
                error[obj.attr('name') + '|' + label] = 'El campo <b>' + obj.data('validate-label') + '</b> es obligatorio';
                break;

            case 'equal':
                error[obj.attr('name') + '|' + label] = 'El campo <b>' + obj.data('validate-label') + '</b> debe ser identico al campo <b>' + $('[name="' + obj.data('validate-equal') + '"]').data('validate-label') + '</b>';
                break;

            case 'email':
                error[obj.attr('name') + '|' + label] = 'El campo <b>' + obj.data('validate-label') + '</b> no es válido';
                break;

            case 'date':
                error[obj.attr('name') + '|' + label] = 'El campo <b>' + obj.data('validate-label') + '</b> debe ser una fecha válida (dd/mm/aaaa)';
                break;

            case 'password':
                error[obj.attr('name') + '|' + label] = 'El campo <b>' + obj.data('validate-label') + '</b> requiere ser una contraseña segura:' +
                                                        '<ul>' +
                                                            '<li>Mínimo 8 caracteres</li>' +
                                                            '<li>Debe contener al menos una mayúscula</li>' +
                                                            '<li>Debe contener al menos una minúscula</li>' +
                                                            '<li>Debe contener al menos un número</li>' +
                                                            '<li>Debe contener al menos un caracter especial (! @ # $ % ^ & * + .)</li>' +
                                                        '</ul>';
                break;
        }
    }

    function unsetLabelError(obj, label)
    {
        delete error[obj.attr('name') + '|' + label];
    }

    function showError()
    {
        result = false;
        html = '<ul class="mb-0">';

        for(var key in error)
        {
            var value = error[key];
            html += '<li>' + value + '</li>';

            objName = key.split('|');
            $('[name="' + objName[0] + '"]').addClass('border-danger');
            $('[name="' + objName[0] + '"]').addClass('bg-danger');
            $('[name="' + objName[0] + '"]').addClass('bg-opacity-10');
        }
        html += '</ul>';

        $('#errorMessage').html(html);

        if(Object.keys(error).length == 0)
        {
            $('#errorAlert').fadeOut();
            result = true;
        }
        else
        {
            $('#errorAlert').fadeIn();
            result = false;
        }

        return result;
    }

    validator.validate = function (form)
    {
        $(form + ' [data-validate]').trigger('validate');
        return showError();
    }

    validator.validateSelector = function (selector)
    {
        $(selector).trigger('validate');
        return showError();
    }

    validator.setRequired = function (form)
    {
        $(form + ' label[for]').each(function()
        {
            input = "#" + $(this).attr('for');
            label = $(this);
            $(form + ' ' + input + "[data-validate~='required']").each(function()
            {
                label.text(label.text() + ' *')
            });
        });
    }
}); 