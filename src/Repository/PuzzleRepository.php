<?php

namespace App\Repository;

use App\Entity\Puzzle;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    public function findLocaleExt($partner, $locale, $first, $limit, $query, &$count) {

        $query = $this->createQueryBuilder('p')
            ->andWhere("p.partner IN({$partner})")
            ->andWhere("p.locale = :locale OR p.locale = '*'")
            ->andWhere("p.published IS NOT NULL")
            ->andWhere("p.title LIKE :query OR p.keywords LIKE :query")
            ->setParameter('locale', $locale)
            ->setParameter('query', "%{$query}%");
            /*
            ->getQuery()
            ->getResult();
    		*/
        /*
        if($query != '') {
            $query = $query;
        }
        */

        $query = $query->orderBy('p.published', 'DESC')
            ->setFirstResult($first)
            ->setMaxResults($limit);

		$paginator = new Paginator($query, true);

		$count = count($paginator);

		return $paginator;
    }

    public function migratePuzzles($partner, $locale, $limit)
    {
        $conn = $this->getEntityManager()
            ->getConnection();
        
        switch($partner) {
        	case '%':
	        	$sql = "
		        	REPLACE INTO `jigsaw-puzzles`.puzzles (id, title, partner, locale, filename, keywords, img_w, img_h, red_w, red_h, created, updated, published)
		            SELECT p.puzz_id, CONCAT('{\"en\": \"', REPLACE(p.title_en, '\"','\\\\\"'), '\", \"fr\": \"', REPLACE(p.title, '\"','\\\\\"'), '\"}'), :partner, :locale, p.filename, p.keywords, p.img_w, p.img_h, p.red_w, p.red_h, p.pub_date, p.pub_date, p.pub_date 
		            	FROM jpuzzles.puzzles p
		            WHERE 1
		            	AND ISNULL(p.partner)
		            ORDER BY p.pub_date DESC
		            LIMIT 0, {$limit}
		            ";
		        break;
		    case '@':
	        	$sql = "
		        	REPLACE INTO `jigsaw-puzzles`.puzzles (id, title, partner, locale, filename, keywords, img_w, img_h, red_w, red_h, created, updated, published)
		            SELECT p.puzz_id, CONCAT('{\"', :locale, '\": \"', REPLACE(p.title, '\"','\\\\\"'), '\"}'), :partner, :locale, p.filename, p.keywords, p.img_w, p.img_h, p.red_w, p.red_h, p.pub_date, p.pub_date, v.validato 
		            	FROM jpuzzles.puzzles p
		            	JOIN jpuzzles.validation v ON p.puzz_id = v.puzz_id
		            WHERE 1
		            	AND p.partner = '{$partner}'
		            	AND v.lang = :locale
		            	AND v.status = 'VALIDATED'
		            ORDER BY v.validato DESC
		            LIMIT 0, {$limit}
		            ";
		        break;
        	default:
	        	$sql = "
		        	REPLACE INTO `jigsaw-puzzles`.puzzles (id, title, partner, locale, filename, keywords, img_w, img_h, red_w, red_h, created, updated, published)
		            SELECT p.puzz_id, CONCAT('{\"', :locale, '\": \"', REPLACE(p.title, '\"','\\\\\"'), '\"}'), :partner, :locale, p.filename, p.keywords, p.img_w, p.img_h, p.red_w, p.red_h, p.pub_date, p.pub_date, p.pub_date 
		            	FROM jpuzzles.puzzles p
		            WHERE 1
		            	AND p.partner = :partner
		            ORDER BY p.pub_date DESC
		            LIMIT 0, {$limit}
		            ";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute([
        	'partner' => $partner,
        	'locale' => $locale,
        ]);

        return $stmt->rowCount();
    }
}