<?php

namespace App\DataFixtures\Content;

use App\DataFixtures\Layout\DefaultLayout;
use App\DataFixtures\Model\PageFixtureModel;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Silverback\ApiComponentBundle\DataFixtures\AbstractFixture;
use Silverback\ApiComponentBundle\Entity\Component\AbstractComponent;
use Silverback\ApiComponentBundle\Entity\Component\ComponentLocation;
use Silverback\ApiComponentBundle\Entity\Component\Content\Content;
use Silverback\ApiComponentBundle\Entity\Content\AbstractContent;
use Silverback\ApiComponentBundle\Entity\Content\Page\StaticPage;
use Silverback\ApiComponentBundle\Entity\Layout\Layout;
use Silverback\ApiComponentBundle\Entity\Route\Route;
use Silverback\ApiComponentBundle\Factory\ContentFactory;
use Silverback\ApiComponentBundle\File\Uploader\FixtureFileUploader;
use Silverback\ApiComponentBundle\Repository\Route\RouteRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractPageFixture extends AbstractFixture implements DependentFixtureInterface
{
    public static $layoutReference = 'layout';
    protected $routeRepository;
    protected $contentFactory;
    protected $fixtureFileUploader;
    protected $sourceFileDir;

    public function __construct(
        ValidatorInterface $validator,
        RouteRepository $routeRepository,
        ContentFactory $contentFactory,
        FixtureFileUploader $fixtureFileUploader,
        string $projectDir = ''
    )
    {
        $this->routeRepository = $routeRepository;
        $this->contentFactory = $contentFactory;
        $this->fixtureFileUploader = $fixtureFileUploader;
        $this->sourceFileDir = $projectDir . '/assets/fixtures';
        parent::__construct($validator);
    }

    /**
     * @param string $routeName
     * @param string $routePath
     * @return PageFixtureModel
     */
    protected function getPage(string $routeName, string $routePath): PageFixtureModel
    {
        /** @var Layout $layout */
        $layout = $this->getReference(self::$layoutReference);

        $route = $this->routeRepository->findOneBy(['name' => $routeName]);
        if ($route) {
            $route->setRoute($routePath);
        } else {
            $route = new Route($routeName, $routePath);
        }
        $page = ($routeContent = $route->getStaticPage()) ?: new StaticPage();
        if (!$routeContent) {
            $page->addRoute($route);
        }
        $page
            ->setLayout($layout)
        ;
        $this->addReference(sprintf('route.%s', $routeName), $route);
        $this->addReference(sprintf('page.%s', $routeName), $page);
        $this->addEntity($page);
        return new PageFixtureModel($route, $page, !$routeContent);
    }

    protected function addComponent(AbstractComponent $component, ?AbstractContent $page = null): ComponentLocation
    {
        $location = new ComponentLocation($page);
        $component->addLocation($location);
        $this->addEntity($component);
        $this->addEntity($location);
        return $location;
    }

    protected function blueBarContent($componentName = 'blue-bar-content'): Content
    {
        $content = $this->contentFactory->create(
            null,
            [
                '1',
                'short'
            ]
        );
        $content->setComponentName($componentName);
        return $content;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            DefaultLayout::class
        ];
    }
}
