<?php

namespace App\DataFixtures\Page;

use App\Form\Handler\ContactHandler;
use App\Form\Type\ContactType;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Component\ContentComponent;
use Silverback\ApiComponentBundle\DataFixtures\Component\FormComponent;
use Silverback\ApiComponentBundle\DataFixtures\Component\HeroComponent;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;

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

        $this->createComponent(HeroComponent::class, $this->entity, [
            'title' => 'Forms',
            'subtitle' => 'An example of a Symfony form served and handled by the API with validation',
            'className' => 'is-success is-bold'
        ]);
        $this->createComponent(ContentComponent::class, $this->entity, [
            'lipsum' => ['2', 'short', 'decorate']
        ]);
        $this->createComponent(FormComponent::class, $this->entity, [
            'formType' => ContactType::class,
            'successHandler' => ContactHandler::class
        ]);

        $this->flush();
        $this->addReference('page.forms', $this->entity);
    }
}
