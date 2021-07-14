<?php

namespace App\Event\Listener;

use App\Event\MailEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailListener
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendMail(MailEvent $event): void
    {
        $email = (new Email())
            ->from('Dentest <noreply@dentest.tech>')
            ->to($event->to)
            ->subject($event->mail->getSubject())
            ->text($event->mail->getPlain());

        $this->mailer->send($email);
    }
}
