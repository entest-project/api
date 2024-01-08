<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity]
class TableStepParam extends StepParam
{
    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'json')]
    public array $content;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $headerColumn = false;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $headerRow = false;
}
