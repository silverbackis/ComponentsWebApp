<?php

namespace App\Util;

use App\Entity\Component\Form\FormView;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormUtils
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

    /**
     * @param string $className
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm (string $className): FormInterface
    {
        return $this->formFactory->create(
            $className,
            null,
            [
                'method' => 'POST',
                // 'action' => $this->generateUrl('form_contact')
            ]
        );
    }

    /**
     * @param FormInterface $form
     * @param $content
     * @return array
     * @throws BadRequestHttpException
     */
    public function deserializeFormData (FormInterface $form, $content): array
    {
        $content = \GuzzleHttp\json_decode($content, true);
        if (!isset($content[$form->getName()])) {
            throw new BadRequestHttpException(
                "Form object key could not be found. Expected: <b>" . $form->getName() . "</b>: { \"input_name\": \"input_value\" }"
            );
        }
        return $content[$form->getName()];
    }

    public function findByClassName (string $className) {
        $form = $this->createForm($className);
        return new FormView($form->createView());
    }
}
