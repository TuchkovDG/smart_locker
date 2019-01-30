<?php

namespace App\EventListener;

use App\Entity\Company;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AuthenticationSuccessListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccess'
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!($user instanceof Company)) {
            return;
        }

        $data['user'] = [
            'email' => $user->getEmail(),
            'name' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'roles' => $user->getRoles(),
        ];

        $event->setData($data);
    }
}
