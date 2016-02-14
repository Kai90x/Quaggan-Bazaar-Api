<?php

require_once('rb.php');

RedBeanPHP\Facade::setup('mysql:host=localhost;dbname=quagganbazaar', 'root', ''); //for both mysql or mariaDB
//RedBeanPHP\Facade::setup('mysql:host=23.229.226.96;dbname=guildwars2db', 'kai_admin', 'OperationKai24%');
RedBeanPHP\Facade::debug(true);
?>