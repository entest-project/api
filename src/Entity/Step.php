<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StepRepository")
 */
class Step
{
    const TYPE_GIVEN = 'given';
    const TYPE_WHEN = 'when';
    const TYPE_THEN = 'then';
    const EXTRA_PARAM_TYPE_NONE = 'none';
    const EXTRA_PARAM_TYPE_MULTILINE = 'multiline';
    const EXTRA_PARAM_TYPE_TABLE = 'table';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="steps")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public Project $project;

    /**
     * @ORM\Column(type="string", columnDefinition="step_type")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public string $type;

    /**
     * @ORM\Column(type="string", columnDefinition="step_extra_param_type")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public string $extraParamType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StepPart", mappedBy="step", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"priority": "ASC"})
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public iterable $parts = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScenarioStep", mappedBy="step", cascade="all", orphanRemoval=true)
     *
     * @Serializer\Exclude
     */
    public iterable $scenarioSteps = [];

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
     *
     * @Serializer\Groups({"READ_STEP"})
     */
    public iterable $tags = [];
}
