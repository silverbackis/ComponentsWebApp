<?php

namespace App\Controller;

use App\Entity\Component\Form\FormView;
use App\Util\FormUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractForm extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var FormUtils
     */
    protected $formResolver;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        FormUtils $formResolver
    )
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->formResolver = $formResolver;
    }

    /**
     * @param $data
     * @param $_format
     * @param $valid
     * @return Response
     */
    protected function getResponse ($data, $_format, $valid, Response $response = null)
    {
        if (!$response) {
            $response = new Response();
        }
        $response->setStatusCode($valid ? Response::HTTP_OK : Response::HTTP_NOT_ACCEPTABLE);
        $response->setContent($this->serializer->serialize($data, $_format, ['groups' => ['page']]));
        return $response;
    }

    /**
     * @param FormView $formView
     * @return mixed
     */
    protected function getFormValid (FormView $formView)
    {
        return $formView->getVars()['valid'];
    }
}
