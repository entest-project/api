<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects", methods={"POST", "PUT"})
 */
class SaveProject extends Api
{
    private EntityManagerInterface $entityManager;
    private ProjectRepository $projectRepository;

    public function __construct(EntityManagerInterface $entityManager, ProjectRepository $projectRepository)
    {
        $this->entityManager = $entityManager;
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
        try {
            $this->projectRepository->save($project);

            return $this->buildSerializedResponse($project, 'READ_PROJECT');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
