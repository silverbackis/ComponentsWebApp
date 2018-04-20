<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Daniel West <daniel@silverback.is>
 */
class JwtEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_INVALID => [['onInvalidExpired', 0]],
            Events::JWT_EXPIRED => [['onInvalidExpired', 0]]
        ];
    }

    /**
     * This will set the response code to forbidden (403)
     * @param AuthenticationFailureEvent $event
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function onInvalidExpired(AuthenticationFailureEvent $event): void
    {
        $response = $event->getResponse();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $response->setContent('The JWT token is invalid or expired, you may need to renew it');
    }
}
