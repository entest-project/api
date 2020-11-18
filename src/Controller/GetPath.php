<?php

namespace App\Controller;

use App\Entity\Path;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paths/{id}", methods={"GET"}, requirements={"id": "[0-9a-z-]+"})
 */
class GetPath extends Api
{
    /**
     * @ParamConverter(
     *     name="path",
     *     class="App\Entity\Path",
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(Path $path): Response
    {
        return $this->buildSerializedResponse($path, 'READ_PATH');
    }
}
