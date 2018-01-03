<?php

namespace App\Entity\Component\Nav\Navbar;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Component\Nav\Nav;
use App\Entity\Component\Nav\NavItemInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity()
 */
class Navbar extends Nav
{
    /**
     * @ORM\OneToMany(targetEntity="NavbarItem", mappedBy="nav")
     * @ORM\OrderBy({"sortOrder" = "ASC"})
     * @Groups({"layout", "page"})
     */
    protected $items;

    public function createNavItem(): NavItemInterface
    {
        return new NavbarItem();
    }
}

