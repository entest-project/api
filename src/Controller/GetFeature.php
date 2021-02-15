<?php

namespace App\Controller;

use App\Repository\FeatureRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/organization/{organizationSlug}/project/{projectSlug}/path/{pathSlug}/features/{featureSlug}",
 *     methods={"GET"},
 *     requirements={
 *         "organizationSlug": "[0-9a-z-]+",
 *         "projectSlug": "[0-9a-z-]+",
 *         "pathSlug": "[0-9a-z-]+",
 *         "featureSlug": "[0-9a-z-]+"
 *     }
 * )
 * @Route(
 *     "/project/{projectSlug}/path/{pathSlug}/features/{featureSlug}",
 *     methods={"GET"},
 *     requirements={
 *         "projectSlug": "[0-9a-z-]+",
 *         "pathSlug": "[0-9a-z-]+",
 *         "featureSlug": "[0-9a-z-]+"
 *     }
 * )
 */
class GetFeature extends Api
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    public function __invoke(string $projectSlug, string $pathSlug, string $featureSlug, string $organizationSlug = null): Response
    {
        try {
            $feature = $this->featureRepository->findOneBySlugs($projectSlug, $pathSlug, $featureSlug, $organizationSlug);
        } catch (\Doctrine\DBAL\Exception $exception) {
            throw new UnprocessableEntityHttpException();
        }

        if (null === $feature) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(Verb::READ, $feature);

        return $this->buildSerializedResponse($feature, 'READ_FEATURE');
    }
}
