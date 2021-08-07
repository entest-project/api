<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class MultilineStepParam extends StepParam
{
    /**
     * @ORM\Column(type="text")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public string $content;
}
