<?php

namespace App\Controller;

use App\Entity\Path;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/paths/{id}",
 *     methods={"GET"},
 *     requirements={
 *         "pathId": "[0-9a-f-]+"
 *     }
 * )
 */
class GetPath extends Api
{
    public function __invoke(Path $path): Response
    {
        $this->denyAccessUnlessGranted(Verb::READ, $path);

        return $this->buildSerializedResponse($path, 'READ_PATH');
    }
}
