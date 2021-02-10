<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Exception\ProjectNotFoundException;
use App\Repository\ProjectRepository;
use App\Security\Voter\Verb;
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

    public function __invoke(Feature $feature): Response
    {
        $this->denyAccessUnlessGranted(Verb::READ, $feature);

        try {
            return $this->buildSerializedResponse($this->projectRepository->findFeatureRootProjectId($feature->id));
        } catch (ProjectNotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }
}
