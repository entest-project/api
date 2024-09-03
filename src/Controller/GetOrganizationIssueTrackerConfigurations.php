<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Organization;
use App\Repository\OrganizationIssueTrackerConfigurationRepository;
use App\Security\Voter\Verb;
use App\Serializer\Groups;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organizations/{slug}/issue-tracker-configurations', requirements: ['slug' => '[0-9a-z-]+'], methods: ['GET'])]
class GetOrganizationIssueTrackerConfigurations extends Api
{
    public function __construct(
        private readonly OrganizationIssueTrackerConfigurationRepository $organizationIssueTrackerConfigurationRepository
    ) {}

    public function __invoke(Organization $organization): Response
    {
        $this->denyAccessUnlessGranted(Verb::LIST_ISSUE_TRACKER_CONFIGURATIONS, $organization);

        $users = $this->organizationIssueTrackerConfigurationRepository->findBy(['organization' => $organization]);

        return $this->buildSerializedResponse($users, Groups::ListOrganizationIssueTrackerConfigurations);
    }
}
