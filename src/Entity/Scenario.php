<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity]
class Scenario
{
    const TYPE_BACKGROUND = 'background';
    const TYPE_OUTLINE = 'outline';
    const TYPE_REGULAR = 'regular';

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    #[ORM\ManyToOne(targetEntity: Feature::class, inversedBy: 'scenarios')]
    public Feature $feature;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'string', columnDefinition: 'scenario_type')]
    public string $type;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'string')]
    public string $title;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\OneToMany(mappedBy: 'scenario', targetEntity: ScenarioStep::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['priority' => 'ASC'])]
    public iterable $steps = [];

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $examples = null;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'integer')]
    public int $priority;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    public iterable $tags = [];
}
