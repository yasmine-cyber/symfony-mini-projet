<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessListener
{
    private $urlGenerator;
    private $logger;

    public function __construct(UrlGeneratorInterface $urlGenerator, LoggerInterface $logger)
    {
        $this->urlGenerator = $urlGenerator;
        $this->logger = $logger;
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        $roles = $user->getRoles();
        $this->logger->info('User authenticated', [
            'email' => $user->getUserIdentifier(),
            'roles' => $roles,
            'class' => get_class($user),
        ]);

        $targetPath = null;
        if (in_array('ROLE_ADMIN', $roles, true)) {
            $targetPath = $this->urlGenerator->generate('admin_dashboard');
        } elseif (in_array('ROLE_ORGANISATEUR', $roles, true)) {
            $targetPath = $this->urlGenerator->generate('organisateur_dashboard');
        } else {
            $targetPath = $this->urlGenerator->generate('evenement_index');
        }

        $this->logger->info('Redirecting user', [
            'email' => $user->getUserIdentifier(),
            'target' => $targetPath,
        ]);

        $event->setResponse(new RedirectResponse($targetPath));
    }
}