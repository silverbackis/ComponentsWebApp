<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Component\Form\Form;

class FormController extends AbstractController
{
    /**
     * @Route(
     *     name="api_forms_patch_item",
     *     path="/forms/{id}.{_format}",
     *     requirements={"id"="\d+"},
     *     defaults={
     *         "_api_resource_class"=Form::class,
     *         "_api_item_operation_name"="PATCH",
     *         "_format"="json"
     *     },
     *     methods={"PATCH"}
     * )
     */
    public function __invoke($data) {
        die(var_dump($data));
        return $data;
    }
}