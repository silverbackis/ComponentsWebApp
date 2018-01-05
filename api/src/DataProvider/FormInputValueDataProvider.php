<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\Component\Form\FormInputValue;

class FormInputValueDataProvider implements ItemDataProviderInterface
{
    /**
     * @param string $resourceClass
     * @param $id
     * @param string|null $operationName
     * @param array $context
     *
     * @return null|FormInputValue
     * @throws ResourceClassNotSupportedException
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        if (FormInputValue::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException();
        }

        $fiv = new FormInputValue();
        $fiv->setId($id);

        return $fiv;
    }
}
