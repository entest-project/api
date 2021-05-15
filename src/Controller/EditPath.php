<?php

namespace App\Controller;

use App\Entity\Path;
use App\Repository\PathRepository;
use App\Security\Voter\Verb;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paths", methods={"PUT"})
 */
class EditPath extends Api
{
    private PathRepository $pathRepository;

    public function __construct(PathRepository $pathRepository)
    {
        $this->pathRepository = $pathRepository;
    }

    /**
     * @ParamConverter(
     *     name="path",
     *     class="App\Entity\Path",
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(Path $path): Response
    {
        $this->denyAccessUnlessGranted(Verb::UPDATE, $path);

        try {
            $this->pathRepository->save($path);

            return $this->buildSerializedResponse($path, 'READ_PATH');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
