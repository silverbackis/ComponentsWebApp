<?php

namespace App\Form\Handler;

use App\Entity\Component\Form\Form;

interface FormHandlerInterface
{
    /**
     * @param Form $form
     * @return mixed
     */
    public function success(Form $form);
}
