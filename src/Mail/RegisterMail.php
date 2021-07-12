<?php

namespace App\Mail;

class RegisterMail implements MailInterface
{
    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function getSubject(): string
    {
        return 'Welcome on Dentest!';
    }

    public function getPlain(): string
    {
        $template = <<<MAIL
Welcome on Dentest!

You are now a user of a platform on which you'll be able to write, read, pull and execute Gherkin features, in order to specify, validate and document your application.

Your username is: %s

Enjoy ;)
MAIL;

        return sprintf($template, $this->params['username']);
    }
}
