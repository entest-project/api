<?php

namespace App\Controller;

use App\Entity\Project;
use App\Manager\ProjectManager;
use App\Security\Voter\Verb;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use RollandRock\ParamConverterBundle\Attribute\EntityArgument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects', methods: ['POST'])]
class CreateProject extends Api
{
    public function __construct(
        private readonly ProjectManager $projectManager
    ) {}

    public function __invoke(#[EntityArgument] Project $project): Response
    {
        $this->denyAccessUnlessGranted(Verb::CREATE, $project);

        $this->validate($project);

        try {
            $this->projectManager->createProject($project, $this->getUser());

            return $this->buildSerializedResponse($project, 'READ_PROJECT');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            throw new ConflictHttpException();
        }
    }
}
