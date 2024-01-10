<?php

namespace App\Controller;

use App\Entity\ProjectUser;
use App\Repository\PathRepository;
use App\Repository\ProjectUserRepository;
use App\Security\TokenExtractor\AuthorizationHeaderTokenExtractor;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pull/paths', methods: ['GET'])]
class PullPaths extends Api
{
    public function __construct(
        private readonly AuthorizationHeaderTokenExtractor $tokenExtractor,
        private readonly PathRepository $pathRepository,
        private readonly ProjectUserRepository $projectUserRepository
    ) {}

    public function __invoke(Request $request): Response
    {
        $token = $this->tokenExtractor->extract($request);
        $projectUser = $this->projectUserRepository->findOneBy(['token' => $token]);

        if (!$projectUser instanceof ProjectUser) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(Verb::PULL, $projectUser);

        return new JsonResponse($this->pathRepository->findFullSlugsByProject($projectUser->project));
    }
}
