<?php

namespace App\Controller;

use App\Repository\OrganizationRepository;
use App\Repository\UserRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users', methods: ['GET'])]
class GetUsers extends Api
{
    public function __construct(
        private readonly OrganizationRepository $organizationRepository,
        private readonly UserRepository $userRepository
    ) {}

    public function __invoke(Request $request): Response
    {
        if (!$request->query->has('q')) {
            return new JsonResponse([]);
        }

        if ($request->query->has('organization')) {
            $organization = $this->organizationRepository->findOneBy(['slug' => $request->query->get('organization')]);

            if (null === $organization) {
                throw new NotFoundHttpException();
            }

            $this->denyAccessUnlessGranted(Verb::UPDATE, $organization);

            $users = $this->userRepository->searchByOrganization($organization, $request->query->get('q'));
        } else {
            $users = $this->userRepository->search($request->query->get('q'));
        }

        return $this->buildSerializedResponse($users, 'LIST_USERS');
    }
}
