<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity]
class InlineStepParam extends StepParam
{
    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'string')]
    public string $content;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\ManyToOne(targetEntity: StepPart::class)]
    public StepPart $stepPart;
}
