<?php

namespace App\Controller;

use App\Entity\Feature;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/features/{id}", methods={"GET"}, requirements={"id": "[0-9a-z-]+"})
 */
class GetFeature extends Api
{
    /**
     * @ParamConverter(
     *     name="feature",
     *     class="App\Entity\Feature",
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(Feature $feature): Response
    {
        return $this->buildSerializedResponse($feature, 'READ_FEATURE');
    }
}
