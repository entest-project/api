<?php

namespace App\Event\Listener\Serializer;

use App\Entity\Organization;
use App\Entity\OrganizationUser;
use App\Entity\User;
use App\Helper\ExtractSerializationGroupHelper;
use App\Helper\UserHelper;
use App\Repository\OrganizationUserRepository;
use App\Security\OrganizationPermission;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class OrganizationPreSerializeListener
{
    private OrganizationUserRepository $organizationUserRepository;

    private UserHelper $userHelper;

    public function __construct(OrganizationUserRepository $organizationUserRepository, UserHelper $userHelper)
    {
        $this->organizationUserRepository = $organizationUserRepository;
        $this->userHelper = $userHelper;
    }

    public function preSerialize(PreSerializeEvent $event): void
    {
        if (array_intersect(ExtractSerializationGroupHelper::extractGroup($event->getContext()), ['READ_FEATURE', 'READ_ORGANIZATION', 'READ_PATH', 'READ_PROJECT']) === []) {
            return;
        }

        $object = $event->getObject();
        $user = $this->userHelper->getUser();

        if (!$object instanceof Organization || !$user instanceof User) {
            return;
        }

        $organizationUser = $this->organizationUserRepository->findOneByUserAndOrganization($user, $object);

        if (!$organizationUser instanceof OrganizationUser) {
            return;
        }

        $object->permissions = count($organizationUser->permissions) > 0 ? $organizationUser->permissions : [OrganizationPermission::READ];
    }
}
