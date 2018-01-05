<?php

namespace App\Entity\Component\Form;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class FormInputValue
 * @package App\Entity\Component\Form
 * @author Daniel West <daniel@silverback.is>
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"page"}},
 *         "denormalization_context"={"groups"={"form_write"}}
 *     },
 *     itemOperations={
 *         "put"={"method"="PUT", "route_name"="api_forms_patch_item"},
 *         "get"={"method"="GET"}
 *     },
 *     collectionOperations={}
 * )
 */
class FormInputValue
{
    /**
     * @ApiProperty(identifier=true)
     * @var string|null
     */
    protected $id;

    /**
     * @ApiProperty()
     * @Groups({"page", "form_write"})
     * @var string
     */
    protected $key;

    /**
     * @ApiProperty()
     * @Groups({"page", "form_write"})
     * @var null|string|array
     */
    protected $value;

    /**
     * @ApiProperty()
     * @Groups({"page"})
     * @var null|FormView
     */
    protected $formView;

    /**
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param null|string $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return array|string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array|string|null $value
     */
    public function setValue($value = null): void
    {
        $this->value = $value;
    }

    /**
     * @return FormView|null
     */
    public function getFormView(): ?FormView
    {
        return $this->formView;
    }

    /**
     * @param FormView|null $formView
     */
    public function setFormView(?FormView $formView): void
    {
        $this->formView = $formView;
    }
}
