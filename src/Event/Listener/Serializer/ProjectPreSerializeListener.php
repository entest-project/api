<?php

namespace App\Event\Listener\Serializer;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\User;
use App\Helper\ExtractSerializationGroupHelper;
use App\Helper\UserHelper;
use App\Repository\ProjectUserRepository;
use App\Security\ProjectPermission;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class ProjectPreSerializeListener
{
    private ProjectUserRepository $projectUserRepository;

    private UserHelper $userHelper;

    public function __construct(ProjectUserRepository $projectUserRepository, UserHelper $userHelper)
    {
        $this->projectUserRepository = $projectUserRepository;
        $this->userHelper = $userHelper;
    }

    public function preSerialize(PreSerializeEvent $event): void
    {
        if (array_intersect(ExtractSerializationGroupHelper::extractGroup($event->getContext()), ['READ_FEATURE', 'READ_PATH', 'READ_PROJECT']) === []) {
            return;
        }

        $object = $event->getObject();
        $user = $this->userHelper->getUser();

        if (!$object instanceof Project || !$user instanceof User) {
            return;
        }

        $projectUser = $this->projectUserRepository->findOneByUserAndProject($user, $object);

        if (!$projectUser instanceof ProjectUser) {
            return;
        }

        $object->permissions = count($projectUser->permissions) > 0 ? $projectUser->permissions : [ProjectPermission::READ];
    }
}
