<?php

namespace App\Controller;

use App\Repository\OrganizationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/organizations", methods={"GET"})
 */
class GetOrganizations extends Api
{
    private OrganizationRepository $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function __invoke(): Response
    {
        return $this->buildSerializedResponse($this->organizationRepository->findBy([], ['name' => 'ASC']), 'LIST_ORGANIZATIONS');
    }
}
