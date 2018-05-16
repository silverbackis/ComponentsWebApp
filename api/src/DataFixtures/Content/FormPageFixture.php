<?php

namespace App\DataFixtures\Content;

use App\Form\Handler\ContactHandler;
use App\Form\Type\ContactType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Content\ContentFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Form\FormFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Hero\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\PageFactory;

class FormPageFixture extends AbstractFixture
{
    /** @var PageFactory */
    private $pageFactory;
    /** @var HeroFactory */
    private $heroFactory;
    /** @var ContentFactory */
    private $contentFactory;
    /** @var FormFactory  */
    private $formFactory;

    /**
     * HomePageFixture constructor.
     * @param PageFactory $pageFactory
     * @param HeroFactory $heroFactory
     * @param ContentFactory $contentFactory
     * @param FormFactory $formFactory
     */
    public function __construct(
        PageFactory $pageFactory,
        HeroFactory $heroFactory,
        ContentFactory $contentFactory,
        FormFactory $formFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->heroFactory = $heroFactory;
        $this->contentFactory = $contentFactory;
        $this->formFactory = $formFactory;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $page = $this->pageFactory->create(
            [
                'title' => 'Forms',
                'metaDescription' => 'Handling Symfony forms in the front-end'
            ]
        );
        $this->addReference('page.form', $page);

        $this->heroFactory->create(
            [
                'title' => 'Forms',
                'subtitle' => 'An example of a Symfony form served and handled by the API with validation',
                'parentContent' => $page,
                'className' => 'is-danger is-bold'
            ]
        );

        $this->contentFactory->create(
            [
                'parentContent' => $page,
                'lipsum' => ['2', 'short']
            ]
        );

        $this->formFactory->create(
            [
                'formType' => ContactType::class,
                'successHandler' => ContactHandler::class,
                'parentContent' => $page
            ]
        );

        $manager->flush();
    }
}
