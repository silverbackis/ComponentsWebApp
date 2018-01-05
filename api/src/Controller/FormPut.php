<?php

namespace App\Controller;

use App\Entity\Component\Form\Form;
use App\Entity\Component\Form\FormView;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FormPut extends AbstractController
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
     * @return Form|Form[]
     */
    public function __invoke(Request $request, Form $data)
    {
        $form = $this->createForm($data->getClassName(),null, [
            'method' => 'POST'
        ]);
        $content = \GuzzleHttp\json_decode($request->getContent(), true);
        $formData = $content[$form->getName()];
        $form->submit($formData, false);
        if (count($formData) > 1) {
            $datum = [];
            foreach ($formData as $key => $value) {
                $dataItem = clone $data;
                $dataItem->setForm(new FormView($form->get($key)->createView()));
                $datum[] = $dataItem;
            }
            return $datum;
        }

        $data->setForm(new FormView($form->get(key($formData))->createView()));
        return $data;
    }
}