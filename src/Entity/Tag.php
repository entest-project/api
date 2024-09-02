<?php

namespace App\Entity;

use App\Repository\TagRepository;
use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Tag
{
    #[Serializer\Groups([
        Groups::ListTags->value,
        Groups::ReadFeature->value,
        Groups::ReadPath->value,
        Groups::ReadStep->value,
        Groups::ReadTag->value
    ])]
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public ?string $id = null;

    #[Serializer\Ignore]
    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    public Project $project;

    #[Serializer\Groups([
        Groups::ListTags->value,
        Groups::ReadFeature->value,
        Groups::ReadPath->value,
        Groups::ReadStep->value,
        Groups::ReadTag->value
    ])]
    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Length(min: 1, max: 50, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $name;

    #[Serializer\Groups([
        Groups::ListTags->value,
        Groups::ReadFeature->value,
        Groups::ReadPath->value,
        Groups::ReadStep->value,
        Groups::ReadTag->value
    ])]
    #[ORM\Column(type: 'string', length: 7)]
    public string $color;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
