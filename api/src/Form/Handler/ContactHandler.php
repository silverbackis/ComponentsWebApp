<?php

namespace App\Form\Handler;

use Silverback\ApiComponentBundle\Entity\Component\Form\Form;
use Silverback\ApiComponentBundle\Form\Handler\FormHandlerInterface;
use Silverback\ApiComponentBundle\Mailer\Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig\Environment;

class ContactHandler implements FormHandlerInterface
{
    private $mailer;
    private $twig;
    private $emailAddress;

    public function __construct(Mailer $mailer, Environment $twig, $emailAddress)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->emailAddress = $emailAddress;
    }

    public function success(Form $form, $submittedData, Request $request)
    {
        $email = $this->twig->load('emails/contact.html.twig');
        $subject  = $email->renderBlock('subject',   $submittedData);
        $bodyHtml = $email->renderBlock('body_html', $submittedData);
        $bodyText = $email->renderBlock('body_text', $submittedData);
        $emailsSent = $this->mailer->sendEmail($this->emailAddress, $subject, $bodyHtml, $bodyText);

        if (!$emailsSent) {
            throw new HttpException(500, 'Failed to add any messages to the email spool');
        }
    }
}
