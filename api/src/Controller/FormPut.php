<?php

namespace App\Controller;

use App\Entity\Component\Form\Form;
use App\Entity\Component\Form\FormView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormPut extends AbstractForm
{
    /**
     * @Route(
     *     name="api_forms_validate_item",
     *     path="/forms/submit/{id}.{_format}",
     *     requirements={"id"="\d+"},
     *     defaults={
     *         "_api_resource_class"=Form::class,
     *         "_api_item_operation_name"="validate_item",
     *         "_format"="jsonld"
     *     }
     * )
     * @Method("PATCH")
     * @param Request $request
     * @param Form $data
     * @param string $_format
     * @return Response
     */
    public function __invoke(Request $request, Form $data, string $_format)
    {
        $form = $this->formResolver->createForm($data->getClassName());
        $formData = $this->formResolver->deserializeFormData($form, $request->getContent());
        $form->submit($formData, false);
        $dataCount = count($formData);

        if ($dataCount === 1) {
            $data->setForm(new FormView($form->get(key($formData))->createView()));
            return $this->getResponse($data, $_format, $this->getFormValid($data->getForm()));
        }

        $datum = [];
        $valid = true;
        foreach ($formData as $key => $value) {
            $dataItem = clone $data;
            $dataItem->setForm(new FormView($form->get($key)->createView()));
            $datum[] = $dataItem;
            if ($valid && !$this->getFormValid($dataItem->getForm())) {
                $valid = false;
            }
        }
        return $this->getResponse($datum, $_format, $valid);
    }
}