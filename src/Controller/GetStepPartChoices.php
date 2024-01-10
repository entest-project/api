<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\StepPart;
use App\Repository\InlineStepParamRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/step-parts/{id}/choices', methods: ['GET'])]
class GetStepPartChoices extends Api
{
    public function __construct(
        private readonly InlineStepParamRepository $stepParamRepository
    ) {}

    public function __invoke(StepPart $stepPart): Response
    {
        $this->denyAccessUnlessGranted(Verb::READ, $stepPart);

        return $this->buildSerializedResponse($this->stepParamRepository->findChoicesForStepPart($stepPart));
    }
}
