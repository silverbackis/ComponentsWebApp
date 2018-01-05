<?php

namespace App\Entity\Component\Form;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Component\Component;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Form
 * @package App\Entity\Component\Form
 * @author Daniel West <daniel@silverback.is>
 * @ORM\Entity()
 * @ORM\EntityListeners({"\App\EntityListener\FormListener"})
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"page"}},
 *         "denormalization_context"={"groups"={"form_write"}}
 *     },
 *     itemOperations={
 *         "get"={"method"="GET"},
 *         "delete"={"method"="DELETE"}
 *     }
 * )
 */
class Form extends Component
{
    /**
     * @ORM\Column(type="string")
     * @Groups({"page", "form_write"})
     * @var string
     */
    private $className;

    /**
     * @Groups({"page"})
     * @var null|FormView
     */
    private $form;

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * @return null|FormView
     */
    public function getForm(): ?FormView
    {
        return $this->form;
    }

    /**
     * @param null|FormView $form
     */
    public function setForm(?FormView $form): void
    {
        $this->form = $form;
    }
}
