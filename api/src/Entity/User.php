<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Silverback\ApiComponentBundle\Entity\User\User as BaseUser;

/**
 * @ORM\Entity()
 */
class User extends BaseUser
{}
