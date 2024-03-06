<?php
namespace bot\admin\adminBot;

use bot\generalFunctions\generalFunctions;

require_once __DIR__ . '/../generalFunctions.php';

define('generalFunction',new generalFunctions());
class adminBot
{
    public function main(): void
    {
        generalFunction->sendMessage("hi admin");
    }
}