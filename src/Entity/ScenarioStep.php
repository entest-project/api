<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class ScenarioStep
{
    const ADVERB_GIVEN = 'given';
    const ADVERB_WHEN = 'when';
    const ADVERB_THEN = 'then';
    const ADVERB_AND = 'and';
    const ADVERB_BUT = 'but';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scenario", inversedBy="steps")
     */
    public Scenario $scenario;

    /**
     * @ORM\Column(type="string", columnDefinition="step_adverb")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public string $adverb;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Step")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public Step $step;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StepParam", mappedBy="step", cascade={"all"}, orphanRemoval=true)
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public iterable $params = [];

    /**
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public int $priority;
}
