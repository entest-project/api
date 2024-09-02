<?php

namespace App\Entity;

use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity]
class TableStepParam extends StepParam
{
    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'json')]
    public array $content;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $headerColumn = false;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    public bool $headerRow = false;
}
