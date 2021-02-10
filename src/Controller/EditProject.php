<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects", methods={"PUT"})
 */
class EditProject extends Api
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
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
        $this->denyAccessUnlessGranted(Verb::UPDATE, $project);

        try {
            $this->projectRepository->save($project);

            return $this->buildSerializedResponse($project, 'READ_PROJECT');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
