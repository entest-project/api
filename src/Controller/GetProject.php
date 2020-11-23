<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects/{id}", methods={"GET"}, requirements={"id": "[0-9a-z-]+"})
 */
class GetProject extends Api
{
    public function __invoke(Project $project): Response
    {
        return $this->buildSerializedResponse($project, 'READ_PROJECT');
    }
}
