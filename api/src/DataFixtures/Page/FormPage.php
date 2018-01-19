<?php

namespace App\DataFixtures\Page;

use App\Form\Handler\ContactHandler;
use App\Form\Type\ContactType;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;

class FormPage extends AbstractPage
{
    /**
     * @var ContactType
     */
    private $formType;
    /**
     * @var ContactHandler
     */
    private $handler;

    public function __construct(
        ContactType $formType,
        ContactHandler $handler
    )
    {
        parent::__construct();
        $this->formType = $formType;
        $this->handler = $handler;
    }

    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Forms');
        $this->entity->setMetaDescription('Forms can be handles by the BW Starter Website API including validation');
        $hero = $this->addHero('Forms', 'An example of a Symfony form served and handled by the API with validation');
        $hero->setClassName('is-success is-bold');
        $this->addContent(['2', 'short', 'decorate']);
        $this->addForm($this->formType, $this->handler);

        $this->flush();
        $this->addReference('page.forms', $this->entity);
    }
}
