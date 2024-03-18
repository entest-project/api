<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\OrganizationIssueTrackerConfiguration;
use App\Repository\OrganizationIssueTrackerConfigurationRepository;
use App\Security\Voter\Verb;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use RollandRock\ParamConverterBundle\Attribute\EntityArgument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organization-issue-tracker-configurations', methods: ['PUT'])]
class SaveOrganizationIssueTrackerConfiguration extends Api
{
    public function __construct(
        private readonly OrganizationIssueTrackerConfigurationRepository $organizationIssueTrackerConfigurationRepository
    ) {}

    public function __invoke(#[EntityArgument] OrganizationIssueTrackerConfiguration $configuration): Response
    {
        $this->denyAccessUnlessGranted(Verb::CREATE, $configuration);

        $this->validate($configuration);

        try {
            $this->organizationIssueTrackerConfigurationRepository->save($configuration);
        } catch (UniqueConstraintViolationException) {
            throw new ConflictHttpException();
        } catch (ORMException) {
            throw new UnprocessableEntityHttpException();
        }

        return $this->buildSerializedResponse($configuration, 'READ_ORGANIZATION_ISSUE_TRACKER_CONFIGURATION');
    }
}
