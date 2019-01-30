<?php

namespace App\EventListener;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener implements EventSubscriberInterface
{
    /** @var Serializer */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onJWTCreated'
        ];
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
        $payload = $event->getData();

        if (!($user instanceof UserInterface)) {
            return;
        }

        $payload['user'] = $this->serializer->toArray($user);
        $event->setData($payload);
    }
}
