<?php

namespace App\Controller;

use App\Entity\Component\Form\Form;
use App\Entity\Component\Form\FormInputValue;
use App\Entity\Component\Form\FormView;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(
     *     name="api_forms_patch_item",
     *     path="/form_input_values/{id}.{_format}",
     *     requirements={"id"="\d+"},
     *     defaults={
     *         "_api_resource_class"=FormInputValue::class,
     *         "_api_item_operation_name"="put",
     *         "_format"="jsonld"
     *     }
     * )
     * @Method("PUT")
     * @param $data
     */
    public function __invoke(Request $request, FormInputValue $data) {
        /**
         * @var $form Form|null
         */
        $formEntity = $this->entityManager->getRepository(Form::class)->find($data->getId());
        if (!$formEntity) {
            return null;
        }

        $form = $this->createForm($formEntity->getClassName(),null, [
            'method' => 'POST'
        ]);
        if (!$form->has($data->getKey())) {
            return new NotAcceptableHttpException("The field submitted does not exist in this form");
        }

        $form->submit([$data->getKey() => $data->getValue()], false);
        $data->setFormView(new FormView($form->get($data->getKey())->createView()));
        return $data;
    }
}