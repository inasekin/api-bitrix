<?php
$file = $_SERVER["DOCUMENT_ROOT"]."/local/modules/cosmo.api/routes/routes.php";
if(!file_exists($file)) $file = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/cosmo.api/routes/routes.php";
return include($file);