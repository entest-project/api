<?php

namespace App\Event;

use App\Mail\MailInterface;

readonly class MailEvent
{
    public const NAME = 'app.mail';

    public function __construct(
        public string $to,
        public MailInterface $mail
    ) {}
}
