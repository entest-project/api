<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TableStepParam extends StepParam
{
    /**
     * @ORM\Column(type="json")
     */
    public array $content;
}
