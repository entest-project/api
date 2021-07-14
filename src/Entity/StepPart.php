<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="string", columnDefinition="step_part_type")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public string $type;

    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     *
     * @Assert\Length(min=1, max=255, normalizer="trim")
     * @Assert\NotBlank(normalizer="trim")
     */
    public string $content;

    /**
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public int $priority;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Step", inversedBy="parts")
     */
    public Step $step;
}
