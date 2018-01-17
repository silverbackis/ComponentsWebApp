<?php

namespace App\DataFixtures\Nav;

use App\DataFixtures\Page\FeaturesPage;
use App\DataFixtures\Page\FormPage;
use App\DataFixtures\Page\GalleryPage;
use App\DataFixtures\Page\HomePage;
use App\DataFixtures\Page\Navigation\Hero\HeroNavbarPage;
use App\DataFixtures\Page\NewsPage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Nav\AbstractNav;

class LayoutNav extends AbstractNav implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);
        $this->getReference('layout.default')->setNav($this->entity);

        $this->addNavItem('Home', 0, $this->getReference('page.home'));
        $navs = $this->addNavItem('Navigation', 0, $this->getReference('page.navigation'));
        $this->addNavItem('Forms', 0, $this->getReference('page.forms'));
        $this->addNavItem('Features', 0, $this->getReference('page.features'));
        $this->addNavItem('Gallery', 0, $this->getReference('page.gallery'));
        $this->addNavItem('News / Blog', 0, $this->getReference('page.news'));

        $this->addReference('nav.layout', $this->entity);
        $this->addReference('nav.layout.navs', $navs);
        $this->flush();
    }

    /**
     * Return page fixtures that this nav uses
     * @return array
     */
    public function getDependencies()
    {
        return [
            HomePage::class,
            FormPage::class,
            HeroNavbarPage::class,
            FeaturesPage::class,
            GalleryPage::class,
            NewsPage::class
        ];
    }
}