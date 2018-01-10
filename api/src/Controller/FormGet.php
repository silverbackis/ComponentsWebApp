<?php

namespace App\Controller;

use App\Entity\Component\Form\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormGet extends AbstractForm
{
    /**
     * @Route(
     *     name="api_forms_get",
     *     path="/forms/{id}",
     *     requirements={"id"="\d+"},
     *     defaults={
     *         "_api_resource_class"=Form::class,
     *         "_api_item_operation_name"="get_form",
     *         "_format"="jsonld"
     *     }
     * )
     * @Method("GET")
     * @param Request $request
     * @param Form $data
     * @param string $_format
     * @return Response
     */
    public function __invoke(Request $request, Form $data, string $_format)
    {
        $response = $this->getResponse($data, $_format, true);
        $response->setCache(
            [
                'last_modified' => $data->getLastModified()
            ]
        );
        return $response;
    }
}