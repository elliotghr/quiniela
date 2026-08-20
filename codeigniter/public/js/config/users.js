$(document).ready(function ()
{
    validator.setRequired('#formNew');
    validator.setRequired('#formEdit');
    modal.edit('/config/users/saveUser', '/config/users/getUser');
    modal.delete('/config/users/deleteUser');
    modal.new('/config/users/newUser');
}); 

