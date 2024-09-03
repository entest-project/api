<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Security\Voter\Verb;
use App\Serializer\Groups;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/features/{id}', requirements: ['id' => '[0-9a-f-]+'], methods: ['GET'])]
class GetFeatureById extends Api
{
    public function __invoke(Feature $feature): Response
    {
        $this->denyAccessUnlessGranted(Verb::READ, $feature);

        return $this->buildSerializedResponse($feature, Groups::ReadFeature);
    }
}
