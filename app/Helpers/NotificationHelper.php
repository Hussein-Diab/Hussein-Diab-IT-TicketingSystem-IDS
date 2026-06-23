<?php
namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    public static function send($userId, $message)
    {
        Notification::create([
            'UserId'  => $userId,
            'Message' => $message,
            'IsRead'  => false,
        ]);
    }

    public static function notifyAdminsAndManagers($message)
    {
        $adminsAndManagers = User::whereIn('RoleId', [1, 4])->get();
        foreach ($adminsAndManagers as $user) {
            self::send($user->Id, $message);
        }
    }

    public static function notifyAgent($agentId, $message)
    {
        self::send($agentId, $message);
    }

    public static function notifyEmployee($userId, $message)
    {
        self::send($userId, $message);
    }
}