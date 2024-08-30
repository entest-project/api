<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity]
class ScenarioStep
{
    const ADVERB_GIVEN = 'given';
    const ADVERB_WHEN = 'when';
    const ADVERB_THEN = 'then';
    const ADVERB_AND = 'and';
    const ADVERB_BUT = 'but';

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    #[ORM\ManyToOne(targetEntity: Scenario::class, inversedBy: 'steps')]
    public Scenario $scenario;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'string', columnDefinition: 'step_adverb')]
    public string $adverb;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\ManyToOne(targetEntity: Step::class, inversedBy: 'scenarioSteps')]
    public ?Step $step = null;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\OneToMany(mappedBy: 'step', targetEntity: StepParam::class, cascade: ['all'], orphanRemoval: true)]
    public iterable $params = [];

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'integer')]
    public int $priority;
}
