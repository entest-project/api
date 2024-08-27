<?php

namespace App\Manager;

use App\Entity\Feature;
use App\Entity\Project;
use App\Repository\FeatureRepository;
use App\Transformer\FeatureToStringTransformer;

readonly class FeatureManager
{
    public function __construct(
        private FeatureRepository $featureRepository,
        private FeatureToStringTransformer $featureToStringTransformer
    ) {}

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function pull(Project $project, string $inlineParameterWrapper, bool $withId): array
    {
        $features = $this->featureRepository->findPullableByRootProject($project);
        $this->featureToStringTransformer->setInlineParameterWrapper($inlineParameterWrapper);

        return array_map(fn (Feature $feature): array => $this->featureToPulledElement($feature, $withId), $features);
    }

    private function featureToPulledElement(Feature $feature, bool $withId): array
    {
        $element = [
            'displayPath' => $feature->getDisplayRootPath(),
            'path' => $feature->getRootPath() . '.feature',
            'feature' => $this->featureToStringTransformer->transform($feature)
        ];

        if ($withId) {
            $element['id'] = $feature->id;
        }

        return $element;
    }
}
