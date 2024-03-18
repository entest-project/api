<?php

namespace App\Controller;

use App\Repository\FeatureRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paths/{pathId}/features/{featureSlug}', requirements: ['pathId' => '[0-9a-f-]+', 'featureSlug' => '[0-9a-z-]+'], methods: ['GET'])]
class GetFeature extends Api
{
    public function __construct(
        private readonly FeatureRepository $featureRepository
    ) {}

    public function __invoke(string $pathId, string $featureSlug): Response
    {
        $feature = $this->featureRepository->findOneBy([
            'path' => $pathId,
            'slug' => $featureSlug
        ]);

        if (null === $feature) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(Verb::READ, $feature);

        return $this->buildSerializedResponse($feature, 'READ_FEATURE');
    }
}
