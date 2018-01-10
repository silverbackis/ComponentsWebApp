<?php

namespace App\Controller;

use App\Entity\Component\Form\Form;
use App\Entity\Component\Form\FormView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FormPost extends AbstractForm
{
    /**
     * @Route(
     *     name="api_forms_validate",
     *     path="/forms/{id}/submit.{_format}",
     *     requirements={"id"="\d+"},
     *     defaults={
     *         "_api_resource_class"=Form::class,
     *         "_api_item_operation_name"="validate_form",
     *         "_format"="jsonld"
     *     }
     * )
     * @Method("POST")
     * @param Request $request
     * @param Form $data
     * @param string $_format
     * @return Response
     */
    public function __invoke(Request $request, Form $data, string $_format)
    {
        $form = $this->formResolver->createForm($data);
        $formData = $this->formResolver->deserializeFormData($form, $request->getContent());
        $form->submit($formData);

        if (!$form->isSubmitted()) {
            return $this->getResponse($data, $_format, false);
        }
        return $this->getResponse($data, $_format, $form->isValid());
    }
}
