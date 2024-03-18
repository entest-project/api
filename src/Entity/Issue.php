<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Issue
{
    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    /**
     * @Serializer\Groups({"READ_FEATURE"})
     */
    #[ORM\Column(type: 'string', length: 256)]
    #[Assert\NotBlank]
    public string $link;

    #[ORM\ManyToOne(targetEntity: Feature::class, inversedBy: 'issues')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Feature $feature;

    /**
     * @Serializer\Exclude()
     */
    #[ORM\Column(type: 'string', enumType: IssueTracker::class)]
    #[Assert\NotBlank]
    public IssueTracker $issueTracker;

    /**
     * @Serializer\VirtualProperty(name="issueTracker")
     * @Serializer\Groups({"READ_FEATURE"})
     */
    public function issueTracker(): string
    {
        return $this->issueTracker->value;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
