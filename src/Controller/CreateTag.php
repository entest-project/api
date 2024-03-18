<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use RollandRock\ParamConverterBundle\Attribute\EntityArgument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects/{tagProject}/tags', methods: ['POST'])]
class CreateTag extends Api
{
    public function __construct(
        private readonly TagRepository $tagRepository
    ) {}

    public function __invoke(Project $tagProject, #[EntityArgument] Tag $tag): Response
    {
        $tag->project = $tagProject;

        $this->denyAccessUnlessGranted(Verb::CREATE, $tag);

        $this->validate($tag);

        try {
            $this->tagRepository->save($tag);

            return $this->buildSerializedResponse($tag, 'READ_TAG');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
