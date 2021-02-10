<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use App\Repository\OrganizationUserRepository;
use App\Security\Voter\Verb;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organizations", methods={"PUT"})
 */
class EditOrganization extends Api
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
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(Organization $organization): Response
    {
        $this->denyAccessUnlessGranted(Verb::UPDATE, $organization);

        try {
            $this->organizationRepository->save($organization);

            return $this->buildSerializedResponse($organization, 'READ_ORGANIZATION');
        } catch (ORMException | OptimisticLockException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (UniqueConstraintViolationException $e) {
            throw new ConflictHttpException($e->getMessage());
        }
    }
}
