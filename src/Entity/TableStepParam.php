<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class TableStepParam extends StepParam
{
    /**
     * @ORM\Column(type="json")
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public array $content;
}
