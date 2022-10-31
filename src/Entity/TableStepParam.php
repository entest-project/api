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

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public bool $headerColumn = false;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     *
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public bool $headerRow = false;
}
