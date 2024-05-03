<?php

namespace bot\generalFunctions;

use bot\generalDBFunctions\generalDBFunctions;
use PDOException;

require_once __DIR__ . '/Telegram.php';
require_once __DIR__ . '/generalConfig.php';
require_once __DIR__ . '/generalDBFunctions.php';


class generalFunctions
{
    private $bot;
    private $generalDBFunctions;

    public function __construct()
    {
        $this->generalDBFunctions = new generalDBFunctions();
        $this->bot = bot;
    }

    public function addStep(): void
    {
        $this->generalDBFunctions->insertToDB("step", ["telegram_id", "telegram_username", "telegram_full_name", "step"], [chatId, telegramUserName, telegramFullName, "home"]);
    }

    public function setStep(string $step, string $chatId = chatId): void
    {
        $this->generalDBFunctions->updateToDB("step", ["step"], ["$step"], "telegram_id='" . $chatId . "'");
    }

    public function getStep()
    {
        return $this->generalDBFunctions->selectFromDB("step", ["*"], "telegram_id='" . chatId . "'")[0]['step'];
    }

    public function getIdFromFullName($fullName)
    {
        return $this->generalDBFunctions->selectFromDB("step", ["telegram_id"], "telegram_full_name='" . $fullName . "'")[0]['telegram_id'];
    }

    public function isAdmin(): bool
    {
        $admins = $this->generalDBFunctions->selectFromDB("admins", ["telegram_id"]);
        foreach ($admins as $admin) {
            if ($admin['telegram_id'] == chatId) {
                return true;
            }
        }
        return false;
    }

    public function addToReadyAll(): void
    {
        try {
            $stmt = db->prepare("TRUNCATE `ready`");
            $stmt->execute();
            $stmt = db->prepare("INSERT INTO ready (id)
                SELECT telegram_id FROM step;");
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle exception or log
        }
    }

    public function isNewUser()
    {
        $rows = $this->generalDBFunctions->selectFromDB('step', ['*'], "telegram_id='" . chatId . "'");
        return (empty($rows));
    }

    public function getFile(): array
    {
        $fileId = $this->detectType()['id'];
        $fileType = $this->detectType()['type'];
        $file = $this->bot->getFile($fileId);
        list($folder, $fileName) = explode('/', $file['result']['file_path'], 2);
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        $this->bot->downloadFile($file['result']['file_path'], $file['result']['file_path']);
        return ['link' => $file['result']['file_path'], 'type' => $fileType];
    }

    public function broadcast($message, $parsMode = NULL, $keyboard = NULL): void
    {
        $this->addToReadyAll();
        $userIds = $this->generalDBFunctions->selectFromDB("ready", ["id"]);
        $x = 0;
        $userIdsCount = count($userIds);
        $this->sendMessage("شروع ارسال به " . $userIdsCount . " نفر", id: ADMIN['mahdi']);

        for ($i = 0; $i < $userIdsCount; $i++) {
            $userId = $userIds[$i]['id'];
            $response = $this->sendMessage(text: $message, id: $userId);
            if (!$response["ok"]) {
                $x++;
            }
            sleep(0.5);
        }
        $this->sendMessage("موفق: " . count($userIds) - $x);
        $this->sendMessage("نا موفق: $x");
        $this->sendMessage("ارسال همگانی تمام شد", NULL);

    }

    public function detectType()
    {
        $type = updateType;
        if ($type === "photo") {
            return ['type' => 'photo', 'id' => photoId];
        } else if ($type === "voice") {
            return ['type' => 'voice', 'id' => voiceId];
        } else if ($type === "document") {
            return ['type' => 'document', 'id' => documentId];
        } else if ($type === "animation") {
            return ['type' => 'animation', 'id' => animationId];
        } else if ($type === "video") {
            return ['type' => 'video', 'id' => videoId];
        } else if ($type === "audio") {
            return ['type' => 'audio', 'id' => audioId];
        }
    }

    public function deleteMessage(int $id): void
    {
        $this->bot->deleteMessage(
            [
                'chat_id' => chatId,
                'message_id' => $id
            ]
        );
    }

    public function sendMessage(string $text, string $id = "", array $keyboardInput = NULL, $replyParameters = null): mixed
    {
        if ($id == "") {
            $id = chatId;
        }
        if ($keyboardInput != NULL) {
            $keyboardInput = json_encode($keyboardInput);
        }
        return $this->bot->sendMessage(
            [
                'chat_id' => $id,
                'text' => $text,
                'reply_markup' => $keyboardInput,
                'reply_parameters' => $replyParameters
            ]
        );
    }


    public function sendMedia(string $type, mixed $media, string $caption = mediaCaption, string $id = chatId, $replyMarkup = null): mixed
    {
        switch ($type) {
            case "photo":
                return $this->bot->sendPhoto(
                    [
                        'chat_id' => $id,
                        'photo' => $media,
                        'caption' => $caption,
                        'reply_markup' => $replyMarkup
                    ]
                );
            case "voice":
                return $this->bot->sendVoice(
                    [
                        'chat_id' => $id,
                        'voice' => $media,
                        'caption' => $caption,
                        'reply_markup' => $replyMarkup
                    ]
                );
            case "document":
                return $this->bot->sendDocument(
                    [
                        'chat_id' => $id,
                        'document' => $media,
                        'caption' => $caption,
                        'reply_markup' => $replyMarkup
                    ]
                );
            case "animation":
                return $this->bot->sendAnimation(
                    [
                        'chat_id' => $id,
                        'animation' => $media,
                        'caption' => $caption,
                        'reply_markup' => $replyMarkup
                    ]
                );
            case "video":
                return $this->bot->sendVideo(
                    [
                        'chat_id' => $id,
                        'video' => $media,
                        'caption' => $caption,
                        'reply_markup' => $replyMarkup
                    ]
                );
            case "audio":
                return $this->bot->sendAudio(
                    [
                        'chat_id' => $id,
                        'audio' => $media,
                        'caption' => $caption,
                        'reply_markup' => $replyMarkup
                    ]
                );
            default:
                return "done";
        }
    }

    public function editMessage(string $chatId = chatId, string $messageId = messageId, string $text = "", array $keyboardInput = [])
    {
        if ($text == "") {
            return $this->bot->editMessageReplyMarkup(
                [
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                    'reply_markup' => json_encode($keyboardInput)
                ]
            );
        } else {
            return $this->bot->editMessageText(
                [
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                    'text' => $text,
                    'reply_markup' => json_encode($keyboardInput)
                ]
            );
        }
    }

    public function sendToAdmin($msgId): void
    {
        $this->forwardMessage(toChatId: ADMIN['mahdi'], messageId: $msgId);
    }

    public function forwardMessage($toChatId, $messageId = messageId, $fromChatId = chatId)
    {
        return $this->bot->forwardMessage(
            [
                'chat_id' => $toChatId,
                'from_chat_id' => $fromChatId,
                'message_id' => $messageId
            ]
        );
    }

    public function isBlackList($userId): bool
    {
        $data = $this->generalDBFunctions->selectFromDB("black_list", ["telegram_id"], "telegram_id='$userId'");
        if (empty($data)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function addBlackList($userId): void
    {
        $this->generalDBFunctions->insertToDB("black_list", ['telegram_id', 'telegram_username'], [$userId, telegramUserName]);
    }
    public function removeBlackList($userId): void
    {
        $this->generalDBFunctions->deleteFromDB("black_list","telegram_id='".$userId."'");
    }
    public function createChatInviteLink($channelId, $hour = 3)
    {
        $seconds = $hour * 3600;
        return $this->bot->createChatInviteLink(
            [
                'chat_id' => $channelId,
                'name' => telegramUserName,
                'expire_date' => time() + $seconds,
                'member_limit' => 1
            ])['result']['invite_link'];
    }
    public function unbanChatMember($channelId,$userId): void
    {
        $this->bot->unbanChatMember(
            [
                'chat_id' => $channelId,
                'user_id' => $userId,
            ]
        );
    }
}
