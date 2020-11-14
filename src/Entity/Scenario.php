<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Scenario
{
    const TYPE_BACKGROUND = 'background';
    const TYPE_OUTLINE = 'outline';
    const TYPE_REGULAR = 'regular';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Feature", inversedBy="scenarios")
     */
    public Feature $feature;

    /**
     * @ORM\Column(type="string", columnDefinition="scenario_type")
     */
    public string $type;

    /**
     * @ORM\Column(type="string")
     */
    public string $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScenarioStep", mappedBy="scenario", cascade={"all"})
     */
    public iterable $steps;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    public ?array $examples = null;

    public function getId(): int
    {
        return $this->id;
    }
}
