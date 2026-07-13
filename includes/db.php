<?php

require "Classes/Database.php";

$db = Database::getConn();
return $db;