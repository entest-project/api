<?php

namespace App\Entity;

use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity]
class InlineStepParam extends StepParam
{
    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'string')]
    public string $content;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\ManyToOne(targetEntity: StepPart::class)]
    public StepPart $stepPart;
}
