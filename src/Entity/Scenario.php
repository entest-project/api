<?php

namespace App\Entity;

use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity]
class Scenario
{
    const TYPE_BACKGROUND = 'background';
    const TYPE_OUTLINE = 'outline';
    const TYPE_REGULAR = 'regular';

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    #[ORM\ManyToOne(targetEntity: Feature::class, inversedBy: 'scenarios')]
    public Feature $feature;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'string', columnDefinition: 'scenario_type')]
    public string $type;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'string')]
    public string $title;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\OneToMany(mappedBy: 'scenario', targetEntity: ScenarioStep::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['priority' => 'ASC'])]
    public iterable $steps = [];

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $examples = null;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'integer')]
    public int $priority;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    public iterable $tags = [];
}
