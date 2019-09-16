<?php

declare(strict_types=1);

namespace App\DoctrineExtension\DBAL\Types;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

final class Citext extends TextType
{
    const CITEXT = 'citext';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::CITEXT;
    }

    /**
     * {@inheritdoc}
     * @throws DBALException
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getDoctrineTypeMapping(self::CITEXT);
    }
}
