<?php

namespace App\DataFixtures\Content;

use Doctrine\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Component\Hero\Hero;

class TermsPrivacyPageFixture extends AbstractPageFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $pageFixture = $this->getPage('terms-privacy', '/terms-privacy');
        if ($pageFixture->isNew()) {
            $page = $pageFixture->getStaticPage();
            $page
                ->setMetaDescription('Terms and privacy')
            ;

            $this->addComponent($this->createHero(), $page);
            $this->addComponent($this->contentFactory->create(), $page);
        }
        $this->flush($manager);
    }

    private function createHero(): Hero
    {
        $hero = new Hero();
        $hero
            ->setTitle('Terms & Privacy')
            ->setClassName('is-primary')
        ;
        return $hero;
    }

//    public function getDependencies(): array
//    {
//        return array_merge(parent::getDependencies(), []);
//    }
}
