<?php

namespace App\Transformer;

use App\Entity\Component\Form\Form;
use App\Entity\Component\Form\FormView;
use Symfony\Component\Form\FormFactoryInterface;

class FormTransformer
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * FormDataProvider constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        FormFactoryInterface $formFactory
    )
    {
        $this->formFactory = $formFactory;
    }

    public function transform (string $className) {
        $form = $this->formFactory->create(
            $className,
            null,
            [
                'method' => 'POST',
                // 'action' => $this->generateUrl('form_contact')
            ]
        );
        return new FormView($form->createView());
    }
}
