<?php

namespace App\DataFixtures\Page;

use App\Form\Handler\ContactHandler;
use App\Form\Type\ContactType;
use Doctrine\Common\Persistence\ObjectManager;

class FormPage extends AbstractPage
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Forms');
        $this->entity->setMetaDescription('Forms can be handles by the BW Starter Website API including validation');
        $this->addHero('Forms', 'An example of a Symfony form served and handled by the API with validation');
        $this->addContent(['2', 'short', 'decorate']);
        $this->addForm(ContactType::class, ContactHandler::class);

        $this->flush();
        $this->addReference('page.forms', $this->entity);
    }
}
