<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseMessagingService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(base_path('firebase/firebase_credentials.json'));
        if (!file_exists(base_path('firebase/firebase_credentials.json'))) {
    throw new \Exception('❌ File Firebase credentials tidak ditemukan!');
}
        $this->messaging = $factory->createMessaging();
    }

    public function sendToToken($fcmToken, $title, $body)
    {
        $message = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification(Notification::create($title, $body));

        return $this->messaging->send($message);
    }
}
