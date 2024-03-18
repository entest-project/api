<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FeatureRepository;
use App\Repository\ProjectRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paths/{pathId}/features/{featureSlug}/issue-tracker-configurations', requirements: ['pathId' => '[0-9a-f-]+', 'featureSlug' => '[0-9a-z-]+'], methods: ['GET'])]
class GetFeatureIssueTrackerConfigurations extends Api
{
    public function __construct(
        private readonly FeatureRepository $featureRepository,
        private readonly ProjectRepository $projectRepository
    ) {}

    public function __invoke(string $pathId, string $featureSlug): Response
    {
        /** @var \App\Entity\Feature $feature */
        $feature = $this->featureRepository->findOneBy([
            'path' => $pathId,
            'slug' => $featureSlug
        ]);

        if (null === $feature) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(Verb::UPDATE, $feature);

        $rootProject = $this->projectRepository->findFeatureRootProject($feature);

        return $this->buildSerializedResponse(
            $rootProject->organization->issueTrackerConfigurations ?? [],
            'READ_FEATURE_ISSUE_TRACKER_CONFIGURATION'
        );
    }
}
