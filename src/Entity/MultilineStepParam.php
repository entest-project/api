<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class MultilineStepParam extends StepParam
{
    /**
     * @ORM\Column(type="string")
     */
    public string $content;
}
