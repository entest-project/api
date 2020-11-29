<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\StepRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects/{id}/steps", methods={"GET"}, requirements={"id": "[0-9a-z-]+"})
 */
class GetProjectSteps extends Api
{
    private StepRepository $stepRepository;

    public function __construct(StepRepository $stepRepository)
    {
        $this->stepRepository = $stepRepository;
    }

    public function __invoke(Project $project): Response
    {
        return $this->buildSerializedResponse($this->stepRepository->findBy(['project' => $project]), 'READ_STEP');
    }
}
