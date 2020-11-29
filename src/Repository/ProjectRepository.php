<?php

namespace App\Repository;

use App\Entity\Project;
use App\Exception\ProjectNotFoundException;
use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Project $project): void
    {
        $this->_em->remove($project);
        $this->_em->flush();
    }

    /**
     * @throws ProjectNotFoundException
     * @throws \Doctrine\DBAL\Exception
     */
    public function findFeatureRootProjectId(string $featureId): array
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
SELECT p.id 
FROM project p
JOIN path_rec pr ON pr.root_id = p.root_path_id
JOIN feature f ON f.path_id = pr.id
WHERE f.id = :featureId;
SQL;
        $result = $this->getEntityManager()->getConnection()->fetchAllAssociative($query, [
            'featureId' => $featureId
        ]);

        if (count($result) === 0) {
            throw new ProjectNotFoundException();
        }

        return $result[0];
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Project $project): void
    {
        $this->_em->persist($project);
        $this->_em->flush();
    }
}
