<?php

namespace App\Event\Listener\Serializer;

use App\Entity\Path;
use App\Helper\ExtractSerializationGroupHelper;
use App\Repository\ProjectRepository;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class PathPreSerializeListener
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function preSerialize(PreSerializeEvent $event): void
    {
        if (array_intersect(ExtractSerializationGroupHelper::extractGroup($event->getContext()), ['LIST_PROJECTS', 'READ_FEATURE', 'READ_PATH']) === []) {
            return;
        }

        $object = $event->getObject();

        if (!$object instanceof Path) {
            return;
        }

        $object->rootProject = $this->projectRepository->findPathRootProject($object);
    }
}
