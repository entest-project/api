<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class StepPart
{
    const TYPE_SENTENCE = 'sentence';
    const TYPE_PARAM = 'param';
    const TYPES = [
        self::TYPE_SENTENCE,
        self::TYPE_PARAM
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public int $id;

    /**
     * @ORM\Column(type="string", columnDefinition="step_part_type")
     */
    public string $type;

    /**
     * @ORM\Column(type="string")
     */
    public string $content;

    /**
     * @ORM\Column(type="integer")
     */
    public int $priority;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Step", inversedBy="parts")
     */
    public Step $step;
}
