<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AccountNotVerifiedAuthenticationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{

    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();
        if (!$passport instanceof Passport) {
            throw new \Exception('Unexpected passport type');
        }

        $user = $passport->getUser();

        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }

        if (!$user->getIsVerified()) {
            throw new AccountNotVerifiedAuthenticationException();
        }

    }

    // if the account not verified yet will be fail!
    public function onLoginFailure(LoginFailureEvent $event)
    {
        if(!$event->getException() instanceof AccountNotVerifiedAuthenticationException){
            return;
        }

        $response = new RedirectResponse(
            $this->router->generate('app_verify_resend_email')
        );
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10], //thanks to that user have to insert valid email and valid password to be called this method
            LoginFailureEvent::class => 'onLoginFailure'
        ];
    }

}