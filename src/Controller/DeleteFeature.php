<?php

namespace App\Controller;

use App\Entity\Feature;
use App\Repository\FeatureRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/features/{id}", methods={"DELETE"}, requirements={"id": "[0-9a-z-]+"})
 */
class DeleteFeature extends Api
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    /**
     * @ParamConverter(
     *     name="feature",
     *     class="App\Entity\Feature",
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(Feature $feature): Response
    {
        try {
            $this->featureRepository->delete($feature);

            return new Response();
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
