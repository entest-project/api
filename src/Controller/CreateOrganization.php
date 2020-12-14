<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Entity\User;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationUserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organizations", methods={"POST"})
 */
class CreateOrganization extends Api
{
    private OrganizationRepository $organizationRepository;
    private OrganizationUserRepository $organizationUserRepository;

    public function __construct(OrganizationRepository $organizationRepository, OrganizationUserRepository $organizationUserRepository)
    {
        $this->organizationRepository = $organizationRepository;
        $this->organizationUserRepository = $organizationUserRepository;
    }

    /**
     * @ParamConverter(
     *     name="organization",
     *     class="App\Entity\Organization",
     *     converter="rollandrock_entity_converter",
     *     options={"properties": {"slug"}}
     * )
     */
    public function __invoke(Organization $organization): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        try {
            $this->organizationRepository->save($organization);
            $this->organizationUserRepository->makeAdmin($user, $organization);

            return $this->buildSerializedResponse($organization, 'READ_ORGANIZATION');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            throw new ConflictHttpException($e->getMessage());
        }
    }
}
