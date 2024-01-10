<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\OrganizationIssueTrackerConfiguration;
use App\Repository\OrganizationIssueTrackerConfigurationRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organization-issue-tracker-configurations/{id}', requirements: ['id' => '[0-9a-f-]+'], methods: ['DELETE'])]
class DeleteOrganizationIssueTrackerConfiguration extends Api
{
    public function __construct(
        private readonly OrganizationIssueTrackerConfigurationRepository $organizationIssueTrackerConfigurationRepository
    ) {}

    public function __invoke(OrganizationIssueTrackerConfiguration $configuration): Response
    {
        $this->denyAccessUnlessGranted(Verb::DELETE, $configuration);

        try {
            $this->organizationIssueTrackerConfigurationRepository->delete($configuration);

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (ORMException) {
            throw new UnprocessableEntityHttpException();
        }
    }
}
