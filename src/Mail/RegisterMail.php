<?php

namespace App\Mail;

readonly class RegisterMail implements MailInterface
{
    public function __construct(
        private array $params
    ) {}

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
