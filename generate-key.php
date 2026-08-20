<?php
include "codeigniter/vendor/codeigniter4/framework/system/Encryption/Encryption.php";
use CodeIgniter\Encryption\Encryption;

echo "\nLlave que puede ser utilizada en el archivo .env para la variable 'encryption.key'\n";
echo "\n" . bin2hex(Encryption::createKey()) . "\n\n";
