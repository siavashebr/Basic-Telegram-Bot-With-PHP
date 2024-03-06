<?php
namespace bot\generalConfig;

use bot\telegram\Telegram;

require_once __DIR__ . '/Telegram.php';

define('TOKEN','6800031006:AAEJroejXqCVzulAZlCVuksfG7CcVNQSouc');


//---------------- Connect Database -----------------//

define('bot',new Telegram(TOKEN));
define("chatId", bot->ChatID() ?? '190274893');
define("userText", bot->Text() ?? '');
define("updateType", bot->getUpdateType() ?? '');
define("telegramUserName", bot->username() ?? 'not set!!');
define("callback_data", bot->Callback_Data() ?? 'not callback');
define("callback_id", bot->Callback_ID() ?? 'not id');
define("forwardFromId", bot->FromID() ?? 'not set');
define("messageId", bot->MessageID() ?? '');
define("replyUsername", bot->FromUsername() ?? "not set!!");
define("data",bot->getData() ?? '');
define("replyToMessageID",bot->ReplyToMessageID() ?? '');
define("replyToMessageFromUserID",bot->ReplyToMessageFromUserID() ?? '');
define("replyToMessageFromUserIDMessage",bot->ReplyToMessageFromUserIDMessage() ?? '');

define("photoId", bot->photoid() ?? '');
define("videoId", bot->videoid() ?? '');
define("audioId", bot->audioid() ?? '');
define("documentId", bot->documentid() ?? '');
define("voiceId", bot->voiceid() ?? '');
define("animationId", bot->gifid() ?? '');
define("videoNoteId", bot->videonoteid() ?? '');
define("mediaCaption", bot->Caption() ?? '');
define("userPhoneNumber", bot->phonenu() ?? '');