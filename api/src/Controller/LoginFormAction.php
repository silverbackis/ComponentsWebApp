<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use App\Entity\LoginUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Silverback\ApiComponentBundle\Controller\AbstractForm;
use Silverback\ApiComponentBundle\Entity\Content\Component\Form\Form;
use Silverback\ApiComponentBundle\Entity\Content\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginFormAction extends AbstractForm
{
    /**
     * @Route(
     *     name="login_form",
     *     path="/login_form/{id}.{_format}",
     *     defaults={
     *         "_api_resource_class"=LoginUser::class,
     *         "_api_item_operation_name"="get_form",
     *         "_format"="jsonld",
     *         "id"="0"
     *     }
     * )
     * @Method({"GET"})
     * @param LoginUser $data
     * @param string $_format
     * @return Response
     */
    public function __invoke(LoginUser $data, string $_format): Response
    {
        $form = $this->createForm(LoginType::class, $data, [
            'action' => '#'
        ])
        ->add('_action', HiddenType::class, [
            'data' => $this->generateUrl('api_login_check'),
            'mapped' => false
        ]);
        $formEntity = new Form();
        $formEntity->setForm(new FormView($form->createView()));
        $response = $this->getResponse(
            $formEntity,
            $_format,
            true
        );
        $response->setSharedMaxAge(0);
        return $response;
    }
}
