<?php

namespace App\Repository;

use App\Entity\Feature;
use App\Entity\Project;
use Doctrine\ORM\EntityRepository;

class FeatureRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Feature $feature): void
    {
        $this->_em->remove($feature);
        $this->_em->flush();
    }

    /**
     * @return Feature[]
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function findPullableByRootProject(Project $project): iterable
    {
        $query = <<<SQL
WITH RECURSIVE path_rec(id, parent_id, root_id) AS (
  SELECT p.id, p.parent_id, p.id AS root_id
  FROM path p
  WHERE p.parent_id IS NULL
UNION ALL
  SELECT p.id, p.parent_id, pr.root_id
  FROM path_rec pr, path p
  WHERE p.parent_id = pr.id
)
SELECT f.id 
FROM project p
JOIN path_rec pr ON pr.root_id = p.root_path_id
JOIN feature f ON f.path_id = pr.id
WHERE p.id = :projectId AND f.status <> :draftStatus;
SQL;
        $result = $this->getEntityManager()->getConnection()->fetchAllAssociative($query, [
            'projectId' => $project->id,
            'draftStatus' => Feature::FEATURE_STATUS_DRAFT
        ]);

        return $this->findBy(['id' => array_map(fn (array $id): string => $id['id'], $result)]);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Feature $feature): void
    {
        $this->_em->persist($feature);
        $this->_em->flush();
    }
}
