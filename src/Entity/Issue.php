<?php

declare(strict_types=1);

namespace App\Entity;

use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Issue
{
    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'string', length: 256)]
    #[Assert\NotBlank]
    public string $link;

    #[ORM\ManyToOne(targetEntity: Feature::class, inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Feature $feature;

    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'string', enumType: IssueTracker::class)]
    #[Assert\NotBlank]
    public IssueTracker $issueTracker;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
