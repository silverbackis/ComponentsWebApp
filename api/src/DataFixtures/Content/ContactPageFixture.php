<?php

namespace App\DataFixtures\Content;

use App\Form\Handler\ContactHandler;
use App\Form\Type\ContactType;
use Doctrine\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Component\Form\Form;
use Silverback\ApiComponentBundle\Entity\Component\Hero\Hero;

class ContactPageFixture extends AbstractPageFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $pageFixture = $this->getPage('contact', '/contact');
        if ($pageFixture->isNew()) {
            $page = $pageFixture->getStaticPage();
            $page
                ->setTitle('Contact')
                ->setMetaDescription('Contact')
            ;

            $hero = (new Hero())
                ->setTitle('Contact')
            ;
            $this->addComponent($hero, $page);

            $content = $this->contentFactory->create(null, [
                '1',
                'short'
            ]);
            $content->setClassName('feature-content');
            $this->addComponent($content, $page);

            $this->addComponent($this->getContactForm(), $page);
        }
        $this->flush($manager);
    }

    public function getContactForm(): Form
    {
        $form = new Form();
        $form
            ->setFormType(ContactType::class)
            ->setSuccessHandler(ContactHandler::class)
            ->setComponentName('ContactForm')
        ;
        return $form;
    }

//    public function getDependencies(): array
//    {
//        return array_merge(parent::getDependencies(), []);
//    }
}
