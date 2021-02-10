<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/features/{id}", methods={"GET"}, requirements={"id": "[0-9a-z-]+"})
 */
class GetFeature extends Api
{
    public function __invoke(Feature $feature): Response
    {
        $this->denyAccessUnlessGranted(Verb::READ, $feature);

        return $this->buildSerializedResponse($feature, 'READ_FEATURE');
    }
}
