<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Issue;
use App\Exception\IssueSynchronizationFailedException;
use App\Exception\IssueTrackerConfigurationNotFoundException;
use App\Exception\ProjectNotFoundException;
use App\Manager\OrganizationIssueTrackerManager;
use App\Security\Voter\Verb;
use RollandRock\ParamConverterBundle\Attribute\EntityArgument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/issues/{id}/sync', methods: ['POST'])]
class SynchronizeIssue extends Api
{
    public function __construct(private readonly OrganizationIssueTrackerManager $manager)
    {
    }

    public function __invoke(#[EntityArgument] Issue $issue): Response
    {
        $this->denyAccessUnlessGranted(Verb::SYNC, $issue);

        try {
            $this->manager->sendToTracker($issue);

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (ProjectNotFoundException | IssueTrackerConfigurationNotFoundException) {
            throw new NotFoundHttpException();
        } catch (IssueSynchronizationFailedException) {
            throw new ServiceUnavailableHttpException();
        }
    }
}
