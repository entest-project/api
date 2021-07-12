<?php

namespace App\Mail;

class ResetPasswordRequestMail implements MailInterface
{
    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function getSubject(): string
    {
        return 'Dentest: reset your password';
    }

    public function getPlain(): string
    {
        $template = <<<MAIL
Hello,

Click on the following link in order to reset your password: %s

Have a nice day!
MAIL;

        return sprintf($template, $this->params['link']);
    }
}
