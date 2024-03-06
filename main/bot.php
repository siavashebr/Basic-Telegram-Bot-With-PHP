<?php
namespace bot\main\bot;

fastcgi_finish_request();

use bot\generalFunctions\generalFunctions;
use bot\admin\adminBot\adminBot;
use bot\user\userBot\userBot;

require_once __DIR__ . '/../generalFunctions.php';
require_once __DIR__ . '/../generalConfig.php';
require_once __DIR__ . '/../admin/adminBot.php';
require_once __DIR__ . '/../user/userBot.php';

define('generalFunctions', new generalFunctions());
define('adminBot', new adminBot());
define('userBot', new userBot());

if(updateType!= NULL)
{
    if(generalFunctions->isNewUser())
    {
        generalFunctions->addStep();
    }
    if(generalFunctions->isAdmin())
    {
        adminBot->main();
    }
    else
    {
        userBot->main();
    }
}