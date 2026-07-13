<?php
require "includes/init.php";


$conn = require "includes/db.php";

Auth::Logout();
Url::redirect('/Login.php');