<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects/{project}/tags", methods={"POST"})
 */
class CreateTag extends Api
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @ParamConverter(
     *     name="tag",
     *     class="App\Entity\Tag",
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(Project $project, Tag $tag): Response
    {
        $tag->project = $project;

        $this->denyAccessUnlessGranted(Verb::CREATE, $tag);

        try {
            $this->tagRepository->save($tag);

            return $this->buildSerializedResponse($tag, 'READ_TAG');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
