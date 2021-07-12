<?php

namespace App\Controller;

use App\Entity\Project;
use App\Manager\ProjectManager;
use App\Security\Voter\Verb;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects", methods={"POST"})
 */
class CreateProject extends Api
{
    private ProjectManager $projectManager;

    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;
    }

    /**
     * @ParamConverter(
     *     name="project",
     *     class="App\Entity\Project",
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(Project $project): Response
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
