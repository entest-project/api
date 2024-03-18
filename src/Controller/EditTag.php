<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use RollandRock\ParamConverterBundle\Attribute\EntityArgument;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects/{project}/tags', methods: ['PUT'])]
class EditTag extends Api
{
    public function __construct(
        private readonly TagRepository $tagRepository
    ) {}

    public function __invoke(Project $project, #[EntityArgument] Tag $tag): Response
    {
        if ($tag->project->id !== $project->id) {
            throw new AccessDeniedException();
        }

        $this->denyAccessUnlessGranted(Verb::UPDATE, $tag);

        $this->validate($tag);

        try {
            $this->tagRepository->save($tag);

            return $this->buildSerializedResponse($tag, 'READ_TAG');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
