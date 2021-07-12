<?php

namespace App\Event;

use App\Mail\MailInterface;

class MailEvent
{
    public const NAME = 'app.mail';

    public string $to;

    public MailInterface $mail;

    public function __construct(string $to, MailInterface $mail)
    {
        $this->to = $to;
        $this->mail = $mail;
    }
}
