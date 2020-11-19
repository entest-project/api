<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public int $id;

    /**
     * @ORM\Column(type="string", columnDefinition="step_part_type")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public string $type;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public string $content;

    /**
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public int $priority;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Step", inversedBy="parts")
     */
    public Step $step;

    public function getId(): int
    {
        return $this->id;
    }
}
