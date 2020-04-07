<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Silverback\ApiComponentBundle\Entity\User\User as BaseUser;

/**
 * @ApiResource(attributes={"security"="is_granted('ROLE_ADMIN') or object.owner == user"})
 * @ORM\Entity()
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{}
