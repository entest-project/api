<?php

namespace App\Controller;

use App\Entity\Path;
use App\Exception\PathNotFoundException;
use App\Repository\PathRepository;
use App\Security\Voter\Verb;
use App\Serializer\Groups;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paths/{id}/root', requirements: ['id' => '[0-9a-f-]+'], methods: ['GET'])]
class GetPathRoot extends Api
{
    public function __construct(
        private readonly PathRepository $pathRepository
    ) {}

    public function __invoke(Path $path): Response
    {
        $this->denyAccessUnlessGranted(Verb::READ, $path);

        try {
            return $this->buildSerializedResponse(
                $this->pathRepository->findRootPath($path),
                Groups::ReadPath
            );
        } catch (PathNotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }
}
