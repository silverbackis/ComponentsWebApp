<?php

namespace App\Form\Handler;

use Psr\Log\LoggerInterface;
use Silverback\ApiComponentBundle\Entity\Component\Form\Form;
use Silverback\ApiComponentBundle\Form\Handler\FormHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class ContactHandler implements FormHandlerInterface
{
    private $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function success(Form $form, $data, Request $request)
    {
        $this->logger->info('Form submitted', [
            'form' => $form->getFormType()
        ]);
    }
}
