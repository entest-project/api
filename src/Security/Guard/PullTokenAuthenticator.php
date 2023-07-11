<?php

namespace App\Security\Guard;

use App\Entity\ProjectUser;
use App\Entity\User;
use App\Exception\InvalidTokenException;
use App\Repository\ProjectUserRepository;
use App\Repository\UserRepository;
use App\Security\Authentication\Token\PullToken;
use App\Security\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Uid\Uuid;

class PullTokenAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    private AuthorizationHeaderTokenExtractor $tokenExtractor;

    private ProjectUserRepository $projectUserRepository;

    private UserRepository $userRepository;

    public function __construct(
        AuthorizationHeaderTokenExtractor $tokenExtractor,
        ProjectUserRepository $projectUserRepository,
        UserRepository $userRepository
    ) {
        $this->tokenExtractor = $tokenExtractor;
        $this->projectUserRepository = $projectUserRepository;
        $this->userRepository = $userRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        throw new UnauthorizedHttpException('Basic realm="Unauthorized", charset="UTF-8"');
    }

    public function supports(Request $request): ?bool
    {
        $token = $this->tokenExtractor->extract($request);

        if ($token === false) {
            return false;
        }

        return Uuid::isValid($token);
    }

    public function authenticate(Request $request): Passport
    {
        $token = $this->tokenExtractor->extract($request);

        if ($token === false) {
            throw new \LogicException('Unable to extract a pull token from the request.');
        }

        /** @var ProjectUser $projectUser */
        $projectUser = $this->projectUserRepository->findOneBy(['token' => $token]);

        if (!$projectUser) {
            throw new InvalidTokenException('Invalid Pull Token');
        }

        $passport = new SelfValidatingPassport(
            new UserBadge(
                $projectUser->user->id,
                fn ($userIdentifier) => $this->loadUser($userIdentifier)
            )
        );

        $passport->setAttribute('projectUser', $projectUser);
        $passport->setAttribute('token', $token);

        return $passport;
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        return new PullToken(
            $passport->getAttribute('projectUser'),
            $passport->getUser(),
            $firewallName,
            $passport->getUser()->getRoles()
        );
    }

    private function loadUser(string $userIdentifier): ?User
    {
        $user = $this->userRepository->find($userIdentifier);

        if ($user instanceof User) {
            $user->roles = ['ROLE_PULL'];
        }

        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewallName): ?Response
    {
        return null;
    }
}
