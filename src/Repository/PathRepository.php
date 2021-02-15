<?php

namespace App\Repository;

use App\Entity\Path;
use Doctrine\ORM\EntityRepository;

class PathRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Path $path): void
    {
        $this->_em->remove($path);
        $this->_em->flush();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findOneBySlugs(string $projectSlug, string $pathSlug, string $organizationSlug = null): ?Path
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

SELECT pa.id 
FROM project p
JOIN path_rec pr ON pr.root_id = p.root_path_id
JOIN path pa ON pa.id = pr.id
%s
WHERE p.slug = :projectSlug
AND pa.slug = :pathSlug
%s
;
SQL;
        $query = sprintf(
            $query,
            $organizationSlug === null ? '' : 'JOIN organization o ON o.id = p.organization_id',
            $organizationSlug === null ? '' : 'AND o.slug = :organizationSlug'
        );

        $params = [
            'pathSlug' => $pathSlug,
            'projectSlug' => $projectSlug
        ];

        if ($organizationSlug !== null) {
            $params['organizationSlug'] = $organizationSlug;
        }

        $pathId = $this->_em->getConnection()->fetchAllAssociative($query, $params);

        if (count($pathId) === 0) {
            return null;
        }

        return $this->find($pathId[0]['id']);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Path $path): void
    {
        $this->_em->persist($path);
        $this->_em->flush();
    }
}
