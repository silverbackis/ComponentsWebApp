<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use App\Entity\LoginUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Silverback\ApiComponentBundle\Controller\AbstractForm;
use Silverback\ApiComponentBundle\Entity\Content\Component\Form\Form;
use Silverback\ApiComponentBundle\Entity\Content\Component\Form\FormView;
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
            'action' => $this->generateUrl('api_login_check')
        ]);
        $formEntity = new Form();
        $formEntity->setForm(new FormView($form->createView()));
        return $this->getResponse(
            $formEntity,
            $_format,
            true
        );
    }
}
