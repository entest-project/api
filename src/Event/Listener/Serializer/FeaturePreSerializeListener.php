<?php

namespace App\Event\Listener\Serializer;

use App\Entity\Feature;
use App\Helper\ExtractSerializationGroupHelper;
use App\Repository\ProjectRepository;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;

class FeaturePreSerializeListener
{
    private ProjectRepository $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function preSerialize(PreSerializeEvent $event): void
    {
        if (array_intersect(ExtractSerializationGroupHelper::extractGroup($event->getContext()), ['READ_FEATURE']) === []) {
            return;
        }

        $object = $event->getObject();

        if (!$object instanceof Feature) {
            return;
        }

        $object->rootProject = $this->projectRepository->findFeatureRootProject($object);
    }
}
