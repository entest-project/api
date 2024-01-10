<?php

namespace App\Controller;

use App\Entity\Path;
use App\Repository\PathRepository;
use App\Security\Voter\Verb;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use RollandRock\ParamConverterBundle\Attribute\EntityArgument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paths', methods: ['POST'])]
class CreatePath extends Api
{
    public function __construct(
        private readonly PathRepository $pathRepository
    ) {}

    public function __invoke(#[EntityArgument] Path $path): Response
    {
        $this->denyAccessUnlessGranted(Verb::CREATE, $path);

        $this->validate($path);

        try {
            $this->pathRepository->save($path);

            return $this->buildSerializedResponse($path, 'READ_PATH');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            throw new ConflictHttpException();
        }
    }
}
