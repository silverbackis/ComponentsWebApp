<?php

namespace App\Controller;

use App\Entity\Component\Form\Form;
use App\Entity\Component\Form\FormView;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FormPut extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    )
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
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
     * @param string $_format
     * @return Response
     */
    public function __invoke(Request $request, Form $data, string $_format)
    {
        $form = $this->createForm($data->getClassName(),null, [
            'method' => 'POST'
        ]);

        $content = \GuzzleHttp\json_decode($request->getContent(), true);

        if (!isset($content[$form->getName()])) {
            throw new BadRequestHttpException(
                "Form object key could not be found. Expected: <b>" . $form->getName() . "</b>: { \"input_name\": \"input_value\" }"
            );
        }

        $formData = $content[$form->getName()];
        $dataCount = count($formData);

        $form->submit($formData, false);

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

    /**
     * @param $data
     * @param $_format
     * @param $valid
     * @return Response
     */
    private function getResponse ($data, $_format, $valid)
    {
        return new Response($this->serializer->serialize($data, $_format), $valid ? Response::HTTP_OK : Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param FormView $formView
     * @return mixed
     */
    private function getFormValid (FormView $formView)
    {
        return $formView->getVars()['valid'];
    }
}