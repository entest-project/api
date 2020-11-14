<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class InlineStepParam extends StepParam
{
    /**
     * @ORM\Column(type="string")
     */
    public string $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StepPart")
     */
    public StepPart $stepPart;
}
