<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

#[ORM\Entity]
class OrganizationIssueTrackerConfiguration
{
    /**
     * @Serializer\Groups({"READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'issueTrackerConfigurations')]
    public Organization $organization;

    /**
     * @Serializer\Exclude
     */
    #[ORM\Id]
    #[ORM\Column(type: 'string', enumType: IssueTracker::class)]
    public IssueTracker $issueTracker;

    /**
     * @Serializer\Groups({})
     * @Serializer\Type("string")
     */
    #[ORM\Column(type: 'string', length: 255)]
    public string $accessToken;

    /**
     * @Serializer\VirtualProperty(name="issueTracker")
     * @Serializer\Groups({"READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public function issueTracker(): string
    {
        return $this->issueTracker->value;
    }
}
