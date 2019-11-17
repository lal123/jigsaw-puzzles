<?php

namespace App\Repository;

use App\Entity\Puzzle;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class PuzzleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Puzzle::class);
    }

    public function findAllPuzzles()
    {
        return $this->findAll([], ['date' => 'DESC']);
    }

    public function migratePuzzles($partner, $locale, $limit)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        
        $sql = "
        	REPLACE INTO `jigsaw-puzzles`.puzzles (id, title, locale, filename, created, updated)
            SELECT p.puzz_id, p.title, :locale, p.filename, p.pub_date, p.pub_date 
            	FROM jpuzzles.puzzles p
            	JOIN jpuzzles.validation v ON p.puzz_id = v.puzz_id
            WHERE 1
            	AND p.partner = :partner
            	AND v.lang = :locale
            	AND v.status = 'VALIDATED'
            ORDER BY v.validato DESC
            LIMIT 0, {$limit}
            ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
        	'partner' => $partner,
        	'locale' => $locale,
        ]);

        return $stmt->rowCount();
    }
}