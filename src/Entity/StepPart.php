<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class StepPart
{
    const TYPE_SENTENCE = 'sentence';
    const TYPE_PARAM = 'param';
    const TYPES = [
        self::TYPE_SENTENCE,
        self::TYPE_PARAM
    ];

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
    #[ORM\Column(type: 'string', columnDefinition: 'step_part_type')]
    public string $type;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\Column(type: 'string')]
    #[Assert\Length(min: 1, max: 255, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $content;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\Column(type: 'integer')]
    public int $priority;

    #[ORM\ManyToOne(targetEntity: Step::class, inversedBy: 'parts')]
    public Step $step;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\Column(type: 'string', nullable: true, columnDefinition: 'step_part_strategy')]
    public ?string $strategy = null;

    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $choices = null;
}
