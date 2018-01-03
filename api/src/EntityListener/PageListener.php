<?php

namespace App\EntityListener;

use App\Entity\Page;
use App\Util\RouteGenerator;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

class PageListener
{
    /**
     * @var RouteGenerator
     */
    private $routeGenerator;

    public function __construct(
        RouteGenerator $routeGenerator
    )
    {
        $this->routeGenerator = $routeGenerator;
    }

    /**
     * @ORM\PrePersist()
     * @param Page $page
     * @param LifecycleEventArgs $event
     */
    public function prePersist (Page $page, LifecycleEventArgs $event): void
    {
        $this->createPageRoute($page, $event);
    }

    /**
     * @ORM\PreUpdate()
     * @param Page $page
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate (Page $page, PreUpdateEventArgs $event): void
    {
        $this->createPageRoute($page, $event);
    }

    /**
     * @ORM\PreFlush()
     * @param PreFlushEventArgs $eventArgs
     */
    public function preFlush (Page $page, PreFlushEventArgs $eventArgs): void
    {
        if ($this->createPageRoute($page, $eventArgs)) {
            $em = $eventArgs->getEntityManager();
            $pageClassMetaData = $em->getClassMetadata(Page::class);
            $uow = $em->getUnitOfWork();
            $uow->recomputeSingleEntityChangeSet($pageClassMetaData, $page);
        }
    }

    private function createPageRoute(Page $page, EventArgs $event): bool
    {
        if (0 === $page->getRoutes()->count()) {
            $this->routeGenerator->createPageRoute($page);
            return true;
        }
        return false;
    }
}
