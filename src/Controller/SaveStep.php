<?php

namespace App\Controller;

use App\Entity\Step;
use App\Repository\StepRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/steps", methods={"POST", "PUT"})
 */
class SaveStep extends Api
{
    private StepRepository $stepRepository;

    public function __construct(StepRepository $stepRepository)
    {
        $this->stepRepository = $stepRepository;
    }

    /**
     * @ParamConverter(
     *     name="step",
     *     class="App\Entity\Step",
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(Step $step): Response
    {
        try {
            $this->stepRepository->save($step);

            return $this->buildSerializedResponse($step, 'READ_STEP');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
