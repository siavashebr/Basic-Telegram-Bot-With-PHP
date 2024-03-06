<?php
namespace bot\test;
use bot\generalDBFunctions\generalDBFunctions;

require_once 'generalDBFunctions.php';
echo "as";

$db = new generalDBFunctions();
$x = $db->selectFromDB("admins",["*"]);
print_r($x);