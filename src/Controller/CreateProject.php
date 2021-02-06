<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Exception\UserNotAllowedToCreateProjectException;
use App\Manager\ProjectManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new UnauthorizedHttpException('Bearer');
        }

        try {
            $this->projectManager->createProject($project, $user);

            return $this->buildSerializedResponse($project, 'READ_PROJECT');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (UserNotAllowedToCreateProjectException $e) {
            throw new AccessDeniedHttpException();
        }
    }
}
