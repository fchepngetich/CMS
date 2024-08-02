<?php
// Get the loaded PHP configuration file
echo 'Loaded php.ini file: ' . php_ini_loaded_file() . "<br>";

// Check where PHP is looking for extensions
echo 'Extension directory: ' . ini_get('extension_dir') . "<br>";

// Check if the intl extension is loaded
echo 'intl extension loaded: ' . (extension_loaded('intl') ? 'Yes' : 'No') . "<br>";

// Check if the extension is listed in the loaded extensions
$loaded_extensions = get_loaded_extensions();
echo 'Loaded extensions: ' . implode(', ', $loaded_extensions) . "<br>";

// Check specific information about the intl extension
$intl_info = new ReflectionExtension('intl');
echo 'intl extension version: ' . $intl_info->getVersion() . "<br>";
echo 'intl extension filename: ' . $intl_info->getFileName() . "<br>";

?>
