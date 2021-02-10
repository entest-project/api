<?php

namespace App\Controller;

use App\Entity\Path;
use App\Repository\PathRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paths/{id}", methods={"DELETE"}, requirements={"id": "[0-9a-z-]+"})
 */
class DeletePath extends Api
{
    private PathRepository $pathRepository;

    public function __construct(PathRepository $pathRepository)
    {
        $this->pathRepository = $pathRepository;
    }

    public function __invoke(Path $path): Response
    {
        $this->denyAccessUnlessGranted(Verb::DELETE, $path);

        try {
            $this->pathRepository->delete($path);

            return new Response();
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
