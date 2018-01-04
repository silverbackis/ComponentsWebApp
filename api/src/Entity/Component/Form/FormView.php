<?php

namespace App\Entity\Component\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

class FormView
{
    /**
     * @Groups({"page"})
     * @var array
     */
    private $vars;

    /**
     * @Groups({"page"})
     * @var ArrayCollection
     */
    private $children;

    /**
     * @Groups({"page"})
     * @var bool
     */
    private $rendered;

    /**
     * @Groups({"page"})
     * @var bool
     */
    private $methodRendered;

    public function __construct(\Symfony\Component\Form\FormView $formViews)
    {
        $this->vars = $formViews->vars;

        $varsToArray = ['choices', 'preferred_choices', 'label_attr', 'errors', 'is_selected'];
        foreach($varsToArray as $varToArray)
        {
            if (isset($this->vars[$varToArray])) {
                $choices = $this->vars[$varToArray];
                $this->vars[$varToArray] = [];
                foreach ($choices as $choice)
                {
                    $this->vars[$varToArray][] = (array) $choice;
                }
            }
        }

        $this->children = new ArrayCollection();
        foreach ($formViews as $formView) {
            $this->addChild($formView);
        }
        $this->rendered = $formViews->isRendered();
        $this->methodRendered = $formViews->isMethodRendered();
    }

    public function addChild(\Symfony\Component\Form\FormView $formViews) {
        $formView = new FormView($formViews);
        $this->children->add($formView);
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function isRendered(): bool
    {
        return $this->rendered;
    }

    /**
     * @return bool
     */
    public function isMethodRendered(): bool
    {
        return $this->methodRendered;
    }
}
