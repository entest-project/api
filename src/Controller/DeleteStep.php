<?php

namespace App\Controller;

use App\Entity\Step;
use App\Repository\StepRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/steps/{id}", methods={"DELETE"})
 */
class DeleteStep extends Api
{
    private StepRepository $stepRepository;

    public function __construct(StepRepository $stepRepository)
    {
        $this->stepRepository = $stepRepository;
    }

    public function __invoke(Step $step): Response
    {
        $this->denyAccessUnlessGranted(Verb::DELETE, $step);

        try {
            $this->stepRepository->delete($step);

            return new Response();
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
