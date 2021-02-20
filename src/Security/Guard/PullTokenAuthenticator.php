<?php

namespace App\Security\Guard;

use App\Entity\ProjectUser;
use App\Exception\InvalidTokenException;
use App\Repository\ProjectUserRepository;
use App\Security\Authentication\Token\PreAuthenticationPullToken;
use App\Security\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Uid\Uuid;

class PullTokenAuthenticator extends AbstractGuardAuthenticator
{
    private AuthorizationHeaderTokenExtractor $tokenExtractor;

    private ProjectUserRepository $projectUserRepository;

    public function __construct(AuthorizationHeaderTokenExtractor $tokenExtractor, ProjectUserRepository $projectUserRepository)
    {
        $this->tokenExtractor = $tokenExtractor;
        $this->projectUserRepository = $projectUserRepository;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new UnauthorizedHttpException('Basic realm="Unauthorized", charset="UTF-8"');
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        $token = $this->tokenExtractor->extract($request);

        if ($token === false) {
            return false;
        }

        return Uuid::isValid($token);
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        if (false === ($token = $this->tokenExtractor->extract($request))) {
            return;
        }

        $preAuthToken = new PreAuthenticationPullToken($token);

        /** @var ProjectUser $projectUser */
        $projectUser = $this->projectUserRepository->findOneBy(['token' => $token]);

        if (!$projectUser) {
            throw new InvalidTokenException('Invalid Pull Token');
        }

        $preAuthToken->setProjectUser($projectUser);

        return $preAuthToken;
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$credentials instanceof PreAuthenticationPullToken) {
            return null;
        }

        $credentials->getProjectUser()->user->roles = ['ROLE_PULL'];

        return $credentials->getProjectUser()->user;
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
