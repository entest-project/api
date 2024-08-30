<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity(repositoryClass: StepRepository::class)]
class Step
{
    const TYPE_GIVEN = 'given';
    const TYPE_WHEN = 'when';
    const TYPE_THEN = 'then';
    const EXTRA_PARAM_TYPE_NONE = 'none';
    const EXTRA_PARAM_TYPE_MULTILINE = 'multiline';
    const EXTRA_PARAM_TYPE_TABLE = 'table';

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'steps')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    public Project $project;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\Column(type: 'string', columnDefinition: 'step_type')]
    public string $type;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\Column(type: 'string', columnDefinition: 'step_extra_param_type')]
    public string $extraParamType;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $extraParamTemplate;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\OneToMany(mappedBy: 'step', targetEntity: StepPart::class, cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['priority' => 'ASC'])]
    public iterable $parts = [];

    /**
     * @Serializer\Exclude
     */
    #[ORM\OneToMany(mappedBy: 'step', targetEntity: ScenarioStep::class, cascade: ['all'], orphanRemoval: true)]
    public iterable $scenarioSteps = [];

    /**
     * @Serializer\Groups({"READ_STEP"})
     */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    public iterable $tags = [];
}
