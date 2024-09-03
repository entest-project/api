<?php

namespace App\Mail;

readonly class ResetPasswordMail implements MailInterface
{
    public function getSubject(): string
    {
        return 'Dentest: confirmation, password reset';
    }

    public function getPlain(): string
    {
        $template = <<<MAIL
Hello,

Your password has been reset on Dentest!

Have a nice day!
MAIL;

        return $template;
    }
}
