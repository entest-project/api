<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Tag
{
    /**
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_STEP", "READ_TAG"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public ?string $id = null;

    /**
     * @Serializer\Exclude
     */
    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    public Project $project;

    /**
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_STEP", "READ_TAG"})
     */
    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Length(min: 1, max: 50, normalizer: 'trim')]
    #[Assert\NotBlank(normalizer: 'trim')]
    public string $name;

    /**
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_STEP", "READ_TAG"})
     */
    #[ORM\Column(type: 'string', length: 7)]
    public string $color;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
