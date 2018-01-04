<?php

namespace App\EntityListener;

use App\Entity\Component\Form\Form;
use App\Resolver\FormResolver;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

class FormListener
{
    /**
     * @var FormResolver
     */
    private $formResolver;

    public function __construct(
        FormResolver $formResolver
    )
    {
        $this->formResolver = $formResolver;
    }

    /**
     * @ORM\PreUpdate()
     * @param Form $form
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate (Form $form, PreUpdateEventArgs $event): void
    {
        $form->setForm($this->formResolver->findByClassName($form->getClassName()));
    }

    /**
     * @ORM\PostLoad()
     * @param Form $form
     * @param LifecycleEventArgs $event
     */
    public function postLoad (Form $form, LifecycleEventArgs $event): void
    {
        $form->setForm($this->formResolver->findByClassName($form->getClassName()));
    }
}