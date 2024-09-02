<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Organization;
use App\Entity\OrganizationUser;
use App\Entity\User;
use App\Helper\ExtractSerializationGroupHelper;
use App\Helper\UserHelper;
use App\Repository\OrganizationUserRepository;
use App\Security\OrganizationPermission;
use App\Serializer\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class OrganizationNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    public function __construct(
        private OrganizationUserRepository $organizationUserRepository,
        private UserHelper $userHelper,
        private ObjectNormalizer $objectNormalizer
    ) {}

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        if (array_intersect(ExtractSerializationGroupHelper::extractGroup($context), [Groups::ReadFeature->value, Groups::ReadOrganization->value, Groups::ReadPath->value, Groups::ReadProject->value]) === []) {
            return $this->objectNormalize($object, $format, $context);
        }

        $user = $this->userHelper->getUser();

        if (!$object instanceof Organization || !$user instanceof User) {
            return $this->objectNormalize($object, $format, $context);
        }

        $organizationUser = $this->organizationUserRepository->findOneByUserAndOrganization($user, $object);

        if (!$organizationUser instanceof OrganizationUser) {
            return $this->objectNormalize($object, $format, $context);
        }

        $object->permissions = count($organizationUser->permissions) > 0 ? $organizationUser->permissions : [OrganizationPermission::READ];

        return $this->objectNormalize($object, $format, $context);
    }

    private function objectNormalize(mixed $object, string $format = null, array $context = [])
    {
        return $this->objectNormalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Organization;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Organization::class => true
        ];
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->objectNormalizer->setSerializer($serializer);
    }
}
