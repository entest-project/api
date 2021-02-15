<?php

namespace App\Controller;

use App\Repository\PathRepository;
use App\Security\Voter\Verb;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/organization/{organizationSlug}/project/{projectSlug}/path/{pathSlug}",
 *     methods={"GET"},
 *     requirements={
 *         "organizationSlug": "[0-9a-z-]+",
 *         "projectSlug": "[0-9a-z-]+",
 *         "pathSlug": "[0-9a-z-]+"
 *     }
 * )
 * @Route(
 *     "/project/{projectSlug}/path/{pathSlug}",
 *     methods={"GET"},
 *     requirements={
 *         "projectSlug": "[0-9a-z-]+",
 *         "pathSlug": "[0-9a-z-]+"
 *     }
 * )
 */
class GetPath extends Api
{
    private PathRepository $pathRepository;

    public function __construct(PathRepository $pathRepository)
    {
        $this->pathRepository = $pathRepository;
    }

    public function __invoke(string $projectSlug, string $pathSlug, string $organizationSlug = null): Response
    {
        try {
            $path = $this->pathRepository->findOneBySlugs($projectSlug, $pathSlug, $organizationSlug);
        } catch (\Doctrine\DBAL\Exception $exception) {
            throw new UnprocessableEntityHttpException();
        }

        $this->denyAccessUnlessGranted(Verb::READ, $path);

        return $this->buildSerializedResponse($path, 'READ_PATH');
    }
}
