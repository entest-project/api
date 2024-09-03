<?php

namespace App\Controller;

use App\Entity\Path;
use App\Security\Voter\Verb;
use App\Serializer\Groups;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paths/{id}', requirements: ['id' => '[0-9a-f-]+'], methods: ['GET'])]
class GetPath extends Api
{
    public function __invoke(Path $path): Response
    {
        $this->denyAccessUnlessGranted(Verb::READ, $path);

        return $this->buildSerializedResponse($path, Groups::ReadPath);
    }
}
