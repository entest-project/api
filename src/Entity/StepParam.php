<?php

namespace App\Entity;

use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string', columnDefinition: 'param_type')]
#[ORM\DiscriminatorMap([
    'inline' => InlineStepParam::class,
    'multiline' => MultilineStepParam::class,
    'table' => TableStepParam::class
])]
abstract class StepParam
{
    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    #[ORM\ManyToOne(targetEntity: ScenarioStep::class, inversedBy: 'params')]
    public ScenarioStep $step;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    public function getType(): string
    {
        return match (static::class) {
            InlineStepParam::class => 'inline',
            MultilineStepParam::class => 'multiline',
            TableStepParam::class => 'table',
        };
    }
}
