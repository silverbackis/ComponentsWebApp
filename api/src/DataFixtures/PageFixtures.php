<?php

namespace App\DataFixtures;

use App\Entity\Component\ComponentGroup;
use App\Entity\Component\Hero;
use App\Entity\Component\Nav\Menu\Menu;
use App\Entity\Component\Nav\Navbar\Navbar;
use App\Entity\Component\Content;
use App\Entity\Component\Nav\NavInterface;
use App\Entity\Component\Nav\Tabs\Tabs;
use App\Entity\Layout;
use App\Entity\Page;
use App\Entity\Route;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PageFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function load(ObjectManager $manager)
    {
        $page = [];
        $hero = [];
        $nav = [];
        $this->manager = $manager;

        /**
         * HOME PAGE
         */
        $page['home'] = $this->addPage(
            'British Websites',
            'Welcome to the BW Starter Website built with the best and latest frameworks. Front-end uses NuxtJS (VueJS) and Bulma. The API uses API Platform (Symfony 4).',
            new Route('/')
        );
        $hero['home'] = $this->addHero($page['home'], 'British Websites', 'Some subtitle here');
        $this->addContent($page['home'], '
        <h1>Welcome Home</h1>
        <p>Page content still to be decided</p>
        ');

        /**
         * SERVICE PAGE
         */
        $page['service'] = $this->addPage(
            'Our Service',
            'All about our personal service'
        );
        $hero['service'] = $this->addHero($page['service'], 'Our Service', 'Our Service');
        $this->addContent($page['service'], '
        <h2>Client Focused</h2>
        <p>...</p>
        
        <h2>Personal Service</h2>
        <p>...</p>
        
        <h2>Client Data</h2>
        <p>...</p>
        
        <h2>Data Collection</h2>
        <p>...</p>
        ');

        /**
         * CONTACT PAGE
         */
        $page['contact'] = $this->addPage(
            'Contact',
            'This could be a contact page.'
        );
        $hero['contact'] = $this->addHero($page['contact'], 'Contact Us', 'Using a form below');
        $this->addContent($page['contact'], '
        <p>Form work to be completed</p>
        ');

        /**
         * WEBSITES HERO TOP
         */
        $page['websites'] = $this->addPage(
            'Websites',
            'Discover our websites'
        );
        $hero['websites'] = $this->addHero($page['websites'], 'Websites', 'Discover our websites');
        $nav['websites_hero'] = $this->addNav();
        $hero['websites']->setNav($nav['websites_hero']);

        /**
         * WEBSITES - THE USER EXPERIENCE
         */
        $page['websites/ux'] = $this->addPage(
            'The User Experience',
            'The importance of a user experience',
            null,
            $page['websites']
        );
        $this->addNavItem($nav['websites_hero'], 'The User Experience', null, $page['websites/ux']);
        $page['websites']->getRoutes()->first()->setRedirect($page['websites/ux']->getRoutes()->first());

        $nav['websites/ux/side'] = $this->addNav($page['websites/ux'], null, new Menu());
        $this->addNavItem($nav['websites/ux/side'], 'The User Experience', null, $page['websites/ux'], 'the-user-experience');
        $this->addNavItem($nav['websites/ux/side'], 'Mobile Device Users', null, $page['websites/ux'], 'mobile-device-users');
        $this->addNavItem($nav['websites/ux/side'], 'Real-time & Responsive', null, $page['websites/ux'], 'realtime-and-responsive');
        $this->addNavItem($nav['websites/ux/side'], 'Micro Animation', null, $page['websites/ux'], 'micro-animation');
        $this->addNavItem($nav['websites/ux/side'], 'User Interface & Design', null, $page['websites/ux'], 'user-interface-and-design');

        $componentGroup = new ComponentGroup();
        $componentGroup->setParent($nav['websites/ux/side']);
        $this->manager->persist($componentGroup);

        $this->addContent(null, '
        <h2>The User Experience</h2>
        <h2>Mobile Device Users</h2>
        <h2>Real-time & Responsive</h2>
        <h2>Micro Animation</h2>
        <h2>User Interface & Design</h2>
        ', $componentGroup);

        /**
         * WEBSITES - BEHIND THE SCENES
         */
        $page['websites/behind_scenes'] = $this->addPage(
            'Behind The Scenes',
            'The important stuff about how we build websites',
            null,
            $page['websites']
        );
        $nav['websites/behind_scenes/tabs'] = $this->addNav($page['websites/behind_scenes'], null, new Tabs());

        /**
         * WEBSITES - BEHIND THE SCENES - SECURITY
         */
        $page['websites/behind_scenes/security'] = $this->addPage(
            'Security',
            'Security concious',
            null,
            $page['websites/behind_scenes']
        );
        $page['websites/behind_scenes']->getRoutes()->first()->setRedirect($page['websites/behind_scenes/security']->getRoutes()->first());

        $this->addNavItem($nav['websites_hero'], 'Behind The Scenes', null, $page['websites/behind_scenes']);
        $this->addNavItem($nav['websites/behind_scenes/tabs'], 'Security', null, $page['websites/behind_scenes/security']);


        $nav['websites/behind_scenes/security/side'] = $this->addNav($page['websites/behind_scenes/security'], null, new Menu());
        $this->addNavItem(
            $nav['websites/behind_scenes/security/side'],
            'Security By Design',
            null,
            $page['websites/behind_scenes/security'],
            'security-by-design'
        );
        $this->addNavItem(
            $nav['websites/behind_scenes/security/side'],
            'SSL / HTTPS',
            null,
            $page['websites/behind_scenes/security'],
            'ssl-https'
        );
        $this->addNavItem(
            $nav['websites/behind_scenes/security/side'],
            'Pen Testing',
            null,
            $page['websites/behind_scenes/security'],
            'pen-testing'
        );
        $this->addNavItem(
            $nav['websites/behind_scenes/security/side'],
            'Hosting',
            null,
            $page['websites/behind_scenes/security'],
            'hosting'
        );

        $componentGroup = new ComponentGroup();
        $componentGroup->setParent($nav['websites/behind_scenes/security/side']);
        $this->manager->persist($componentGroup);

        $this->addContent(null, '
        <h2>Security By Design</h2>
        <h2>SSL / HTTPS</h2>
        <h2>Pen Testing</h2>
        <h2>Hosting</h2>
        ', $componentGroup);

        /**
         * WEBSITES - BEHIND THE SCENES - SEO
         */
        $page['websites/behind_scenes/seo'] = $this->addPage(
            'Search Engine Friendly',
            'Making websites search engine friendly',
            null,
            $page['websites/behind_scenes']
        );
        $this->addNavItem($nav['websites/behind_scenes/tabs'], 'Search Engines', null, $page['websites/behind_scenes/seo']);

        $nav['websites/behind_scenes/seo/side'] = $this->addNav($page['websites/behind_scenes/seo'], null, new Menu());
        $this->addNavItem(
            $nav['websites/behind_scenes/seo/side'],
            'Structured Data (Microdata)',
            null,
            $page['websites/behind_scenes/seo'],
            'structured-data'
        );
        $this->addNavItem(
            $nav['websites/behind_scenes/seo/side'],
            'Indexable',
            null,
            $page['websites/behind_scenes/seo'],
            'indexable'
        );
        $this->addNavItem(
            $nav['websites/behind_scenes/seo/side'],
            'Content standards & recommendations',
            null,
            $page['websites/behind_scenes/seo'],
            'content-standards'
        );

        $componentGroup = new ComponentGroup();
        $componentGroup->setParent($nav['websites/behind_scenes/seo/side']);
        $this->manager->persist($componentGroup);

        $this->addContent(null, '
        <h2>Structured Data (Microdata)</h2>
        <h2>Indexable</h2>
        <h2>Content standards & recommendations</h2>
        ', $componentGroup);

        /**
         * WEBSITES - BEHIND THE SCENES - FRAMEWORK AND STABILITY
         */
        $page['websites/behind_scenes/frameworks'] = $this->addPage(
            'Framework & Stability',
            'The frameworks we use',
            null,
            $page['websites/behind_scenes']
        );
        $this->addNavItem($nav['websites/behind_scenes/tabs'], 'Framework & Stability', null, $page['websites/behind_scenes/frameworks']);
        $this->addContent($page['websites/behind_scenes/frameworks'], '
        <h2>Frameworks</h2>
        <h2>Stability</h2>
        ');

        /**
         * WEBSITES - EXAMPLES
         */
        $page['websites/examples'] = $this->addPage(
            'Examples',
            'Examples of our websites',
            null,
            $page['websites']
        );
        $this->addContent($page['websites/examples'], '
        <h2>Some examples</h2>
        ');
        $this->addNavItem($nav['websites_hero'], 'Examples', null, $page['websites/examples']);


        /**
         * LAYOUT
         */
        $layout = $this->addLayout();

        /**
         * LAYOUT NAV
         */
        $layoutNav = $this->addNav(null, $layout);

        $this->addNavItem($layoutNav, 'Home', 0, $page['home']);
        $websitesNavItem = $this->addNavItem($layoutNav, 'Websites', 1, $page['websites']);
        $this->addNavItem($layoutNav, 'Service', 2, $page['service']);
        $this->addNavItem($layoutNav, 'Contact', 3, $page['contact']);

        /**
         * LAYOUT NAV - WEBSITES DROPDOWN
         */
        $nav['websites'] = $this->addNav();
        $websitesNavItem->setChild($nav['websites']);
        $this->addNavItem($nav['websites'], 'The User Experience', null, $page['websites/ux']);
        $this->addNavItem($nav['websites'], 'Behind The Scenes', null, $page['websites/behind_scenes']);
        $this->addNavItem($nav['websites'], 'Examples', null, $page['websites/examples']);


        $manager->flush();
    }

    private function addLayout (): Layout
    {
        $layout = new Layout();
        $layout->setDefault(true);
        $this->manager->persist($layout);
        return $layout;
    }

    private function addPage(string $title, string $description, Route $route = null, Page $parent = null)
    {
        $page = new Page();
        $page->setTitle($title);
        $page->setMetaDescription($description);
        if (null !== $route) {
            $route->setPage($page);
            $this->manager->persist($route);
            $page->addRoute($route);
        }
        $page->setParent($parent);
        $this->manager->persist($page);
        return $page;
    }

    private function addNav(Page $page = null, Layout $layout = null, NavInterface $nav = null)
    {
        if (!$nav) {
            $nav = new Navbar();
        }

        if ($page) {
            $nav->setPage($page);
        }
        if ($layout) {
            $layout->setNav($nav);
        }
        $this->manager->persist($nav);
        return $nav;
    }

    private function addNavItem(NavInterface $nav, string $navLabel, int $order = null, Page $page = null, string $fragment = null)
    {
        if (null === $order) {
            // auto ordering
            $lastItem = $nav->getItems()->last();
            if (!$lastItem) {
                $order = 0;
            } else {
                $order = $lastItem->getSortOrder() + 1;
            }
        }

        $navItem = $nav->createNavItem();
        $navItem->setLabel($navLabel);
        $navItem->setSortOrder($order);
        $navItem->setRoute($page->getRoutes()->first());
        $navItem->setFragment($fragment);
        $nav->addItem($navItem);
        $this->manager->persist($navItem);

        return $navItem;
    }

    private function addHero(Page $page, string $title, string $subtitle = null)
    {
        $hero = new Hero();
        $hero->setPage($page);
        $hero->setTitle($title);
        $hero->setSubtitle($subtitle);
        $this->manager->persist($hero);
        return $hero;
    }

    private function addContent(Page $page = null, string $content, ComponentGroup $componentGroup = null)
    {
        $textBlock = new Content();
        if ($page) {
            $textBlock->setPage($page);
        }
        if ($componentGroup) {
            $textBlock->setGroup($componentGroup);
        }
        $textBlock->setContent($content);
        $this->manager->persist($textBlock);
        return $textBlock;
    }
}
