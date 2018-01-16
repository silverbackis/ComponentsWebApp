<?php

namespace App\Form\Handler;

use App\Entity\Component\Form\Form;
use Psr\Log\LoggerInterface;

class ContactHandler implements FormHandlerInterface
{
    private $logger;

    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    public function success(Form $form)
    {
        $this->logger->info('Form submitted', [
            'form' => $form->getClassName()
        ]);
    }
}
