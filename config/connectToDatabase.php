<?php


   $configDb = require_once($_SERVER['DOCUMENT_ROOT'] . '/config/configDatabase.php');

   $DB =  Database::getInstance()->connectToDb($configDb);

?>