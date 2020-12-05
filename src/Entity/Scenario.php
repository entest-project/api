<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Feature", inversedBy="scenarios")
     */
    public Feature $feature;

    /**
     * @ORM\Column(type="string", columnDefinition="scenario_type")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public string $type;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public string $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScenarioStep", mappedBy="scenario", cascade={"all"}, orphanRemoval=true)
     *
     * @Serializer\Groups({"READ_FEATURE"})
     * @ORM\OrderBy({"priority": "ASC"})
     */
    public iterable $steps = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public ?array $examples = null;
}
