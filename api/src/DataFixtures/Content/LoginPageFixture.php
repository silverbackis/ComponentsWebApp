<?php

namespace App\DataFixtures\Content;

use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Component\Form\Form;
use Silverback\ApiComponentBundle\Entity\Component\Hero\Hero;
use Silverback\ApiComponentBundle\Form\Type\LoginType;

class LoginPageFixture extends AbstractPageFixture
{
    public function load(ObjectManager $manager): void
    {
        $pageFixture = $this->getPage('login', '/login');
        if ($pageFixture->isNew()) {
            $page = $pageFixture->getStaticPage();
            $page
                ->setTitle('Admin Login')
                ->setMetaDescription('')
            ;

            $this->addComponent((new Hero())->setTitle('Admin Login'), $page);

            $form = new Form();
            $form
                ->setFormType(LoginType::class)
            ;
            $this->addComponent($form, $page);
        }
        $this->flush($manager);
    }
}
