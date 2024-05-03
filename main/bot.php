<?php
namespace bot\main\bot;

fastcgi_finish_request();

use bot\generalDBFunctions\generalDBFunctions;
use bot\generalFunctions\generalFunctions;
use bot\admin\adminBot\adminBot;
use bot\user\userBot\userBot;

require_once __DIR__ . '/../generalFunctions.php';
require_once __DIR__ . '/../generalConfig.php';
require_once __DIR__ . '/../admin/adminBot.php';
require_once __DIR__ . '/../user/userBot.php';

define('generalFunctions', new generalFunctions());
define('database', new generalDBFunctions());

define('adminBot', new adminBot());
define('userBot', new userBot());

if(updateType!= NULL)
{

    if(updateType == "callback_query")
    {
        bot->answerCallbackQuery(
            [
                'callback_query_id' => callback_id
            ]
        );
    }
    if(generalFunctions->isNewUser() and !str_starts_with(chatId,'-'))
    {
        generalFunctions->addStep();
    }
    if(str_starts_with(chatId,'-'))
    {
        return;
    }
    else if(generalFunctions->isBlackList(chatId))
    {
        generalFunctions->sendMessage('شما در لیست سیاه قرار دارید');
    }
    if(callback_data == 'back' or userText == 'بازگشت')
    {
        if(generalFunctions->isAdmin())
        {
            adminBot->mainMenu();
        }
        else
        {
            userBot->mainMenu();
        }
    }
    else if(generalFunctions->isAdmin())
    {
        adminBot->main();
    }
    else
    {
        userBot->main();
    }
}
