<?php

namespace App\Mail;

interface MailInterface
{
    public function getSubject(): string;

    public function getPlain(): string;
}
