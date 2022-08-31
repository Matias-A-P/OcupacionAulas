<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Entity\UserEdificios;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;

    private $edificio = 1;

    public function __construct(UrlGeneratorInterface $urlGenerator, private ManagerRegistry $doctrine)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): Passport
    {
        $dni = $request->request->get('dni', '');
        $this->edificio = $request->request->get('edificio', 1);

        // verificar que tenga acceso al edificio
        $u = $this->doctrine->getRepository(User::class)->findBy(['dni' => $dni]);
        if (empty($u)) {
            $uId = 0;
        } else {
            $uId = $u[0]->getId();
        }
        $ue = $this->doctrine->getRepository(UserEdificios::class)->findBy(['user' => $uId, 'edificio' => $this->edificio]);
        if ((empty($ue)) or ($ue[0]->getEdificio()->getId() <> $this->edificio)) {
            $this->edificio = 0; 
            return new Passport(new UserBadge(0), new PasswordCredentials(''));
        };

        $request->getSession()->set(Security::LAST_USERNAME, $dni);

        return new Passport(
            new UserBadge($dni),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('ocupacion_index', ['edificio' => $this->edificio]));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');
    }
}
