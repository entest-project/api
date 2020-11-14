<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    public int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Scenario", inversedBy="steps")
     */
    public Scenario $scenario;

    /**
     * @ORM\Column(type="string", columnDefinition="step_adverb")
     */
    public string $adverb;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Step")
     */
    public Step $step;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StepParam", mappedBy="step", orphanRemoval=true)
     */
    public iterable $params;

    public function getId(): int
    {
        return $this->id;
    }
}
