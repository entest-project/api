<?php

namespace App\Controller;

use App\Exception\ProjectNotFoundException;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/features/{id}/root-project", methods={"GET"}, requirements={"id": "[0-9a-z-]+"})
 */
class GetFeatureRootProject extends Api
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function __invoke(string $id): Response
    {
        try {
            return $this->buildSerializedResponse($this->projectRepository->findFeatureRootProjectId($id));
        } catch (ProjectNotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }
}
