<?php

namespace App\Util;

use App\Entity\Component\Form\Form;
use App\Entity\Component\Form\FormView;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\RouterInterface;

class FormUtils
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * FormDataProvider constructor.
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RouterInterface $router
    )
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @param string $className
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm (Form $component): FormInterface
    {
        return $this->formFactory->create(
            $component->getClassName(),
            null,
            [
                'method' => 'POST',
                'action' => $this->router->generate('api_forms_validate', [
                    'id' => $component->getId()
                ])
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

    public function findByClassName (Form $component) {
        $form = $this->createForm($component);
        return new FormView($form->createView());
    }
}
