<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class OrganizationIssueTrackerConfiguration
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", inversedBy="issueTrackerConfigurations")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_ORGANIZATION", "READ_PATH", "READ_PROJECT"})
     */
    public Organization $organization;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", enumType="App\Entity\IssueTracker")
     *
     * @Serializer\Exclude
     */
    public IssueTracker $issueTracker;

    /**
     * @ORM\Column(type="string", length=256)
     *
     * @Serializer\Groups({})
     * @Serializer\Type("string")
     */
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