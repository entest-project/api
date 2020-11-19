<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class InlineStepParam extends StepParam
{
    /**
     * @ORM\Column(type="string")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public string $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StepPart")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public StepPart $stepPart;
}
