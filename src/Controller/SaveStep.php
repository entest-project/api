<?php

namespace App\Controller;

use App\Entity\Step;
use App\Repository\StepRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use RollandRock\ParamConverterBundle\Attribute\EntityArgument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/steps', methods: ['POST', 'PUT'])]
class SaveStep extends Api
{
    public function __construct(
        private readonly StepRepository $stepRepository
    ) {}

    public function __invoke(#[EntityArgument] Step $step): Response
    {
        $this->denyAccessUnlessGranted(Verb::UPDATE, $step);

        $this->validate($step);

        try {
            $this->stepRepository->save($step);

            return $this->buildSerializedResponse($step, 'READ_STEP');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
