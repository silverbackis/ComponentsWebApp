<?php

namespace App\Entity\Component\Form;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Class FormInputValues
 * @package App\Entity\Component\Form
 * @author Daniel West <daniel@silverback.is>
 * @ApiResource(
 *     itemOperations={ "put"={"method"="PUT"} },
 *     collectionOperations={}
 * )
 */
class FormInputValueCollection
{
    /**
     * @ApiProperty(identifier=true)
     * @var int|null
     */
    protected $id;

    /**
     * @ApiProperty()
     * @var FormInputValue[]
     */
    protected $inputValues;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return FormInputValue[]
     */
    public function getInputValues(): array
    {
        return $this->inputValues;
    }

    /**
     * @param FormInputValue[] $inputValues
     */
    public function setInputValues(array $inputValues): void
    {
        $this->inputValues = $inputValues;
    }
}
