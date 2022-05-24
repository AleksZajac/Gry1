<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Films;
use App\Entity\Game;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;


/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;
    /**
     * Query all records.
     *
     * @param array $filters Filters array
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial game.{id, title}',
                'partial category.{id, name}'
            )
            ->join('game.category', 'category')

            ->orderBy('game.title', 'DESC');
        $queryBuilder = $this->applyFiltersToList($queryBuilder, $filters);

        return $queryBuilder;
    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Game $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Game $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('game');
    }

    /**
     * Save record.
     *
     * @param Game $game Tag entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Game $game): void
    {
        $this->_em->persist($game);
        $this->_em->flush($game);
    }
    /**
     * Query Game by name.
     *
     * @param null $id
     *
     * @return int|mixed|string
     */
    public function queryById($id = null)
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($id)) {
            $queryBuilder->andWhere('l.id LIKE :id')
                ->setParameter('id', '%'.$id.'%');
        }

        return $queryBuilder->getQuery()->execute();
    }
    /**
     * Query  by id.
     *
     * @param null $id
     *
     * @return QueryBuilder Query builder
     */
    public function queryById1($id = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($id)) {
            $queryBuilder->andWhere('l.id LIKE :id')
                ->setParameter('id', '%'.$id.'%');
        }

        return $queryBuilder;
    }
    /**
     * Query game by name.
     *
     * @param null $title
     *
     * @return QueryBuilder Query builder
     */
    public function queryByTitle($title = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($title)) {
            $queryBuilder->andWhere('game.title LIKE :title')
                ->setParameter('title', '%'.$title.'%');
        }

        return $queryBuilder;
    }


    /**
     * Delete record.
     *
     * @param Game $game Game entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Game $game)
    {
        $this->_em->remove($game);
        $this->_em->flush($game);
    }
    // /**
    //  * @return Game[] Returns an array of Game objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder $queryBuilder Query builder
     * @param array        $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
    }
    /**
     * FindByExampleField.
     *
     * @param Value $value
     *
     * @return Game[] Returns an array of Game objects
     */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
}
