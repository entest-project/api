<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Project;
use App\Entity\ProjectUser;
use App\Entity\User;
use App\Helper\ExtractSerializationGroupHelper;
use App\Helper\UserHelper;
use App\Repository\ProjectUserRepository;
use App\Security\ProjectPermission;
use App\Serializer\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ProjectNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    public function __construct(
        private ProjectUserRepository $projectUserRepository,
        private UserHelper $userHelper,
        private ObjectNormalizer $objectNormalizer
    ) {}

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        if (array_intersect(ExtractSerializationGroupHelper::extractGroup($context), [Groups::ReadFeature->value, Groups::ReadPath->value, Groups::ReadProject->value]) === []) {
            return $this->objectNormalizer->normalize($object, $format, $context);
        }

        $user = $this->userHelper->getUser();

        if (!$object instanceof Project || !$user instanceof User) {
            return $this->objectNormalizer->normalize($object, $format, $context);
        }

        $projectUser = $this->projectUserRepository->findOneByUserAndProject($user, $object);

        if (!$projectUser instanceof ProjectUser) {
            return $this->objectNormalizer->normalize($object, $format, $context);
        }

        $object->permissions = count($projectUser->permissions) > 0 ? $projectUser->permissions : [ProjectPermission::READ];

        return $this->objectNormalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Project;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Project::class => true
        ];
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->objectNormalizer->setSerializer($serializer);
    }
}
