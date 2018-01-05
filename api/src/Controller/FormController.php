<?php

namespace App\Controller;

use App\Entity\Component\Form\Form;
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
     *     path="/forms/validate/{id}.{_format}",
     *     requirements={"id"="\d+"},
     *     defaults={
     *         "_api_resource_class"=Form::class,
     *         "_api_item_operation_name"="validate_item",
     *         "_format"="jsonld",
     *         "value"=""
     *     }
     * )
     * @Method("PATCH")
     * @param Request $request
     * @param Form $data
     * @param string $key
     * @return Form
     */
    public function __invoke(Request $request, Form $data) {

        $form = $this->createForm($data->getClassName(),null, [
            'method' => 'POST'
        ]);
        $content = \GuzzleHttp\json_decode($request->getContent());

        if (!$form->has($content->key)) {
            throw new NotAcceptableHttpException("The field submitted does not exist in this form");
        }

        $form->submit([$content->key => $content->value], false);
        $data->setForm(new FormView($form->get($content->key)->createView()));

        return $data;
    }
}