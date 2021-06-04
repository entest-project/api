<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projects/{project}/tags", methods={"GET"})
 */
class GetTags extends Api
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function __invoke(Project $project): Response
    {
        return $this->buildSerializedResponse($this->tagRepository->findBy(['project' => $project]), 'LIST_TAGS');
    }
}
