<?php
/*
 * FavoriteGames
 */
namespace App\Repository;

use App\Entity\FavoriteGames;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriteGames|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteGames|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteGames[]    findAll()
 * @method FavoriteGames[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteGamesRepository extends ServiceEntityRepository
{
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
     * FavoriteGameRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteGames::class);
    }
    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(int $id): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('favorite_games', 'game')
            ->leftJoin('favorite_games.game', 'game')
            ->andWhere('favorite_games.user = :user')
            ->setParameter('user', $id)
            ->orderBy('favorite_games.id', 'DESC');
    }
    /**
     * FindByExampleField.
     *
     * @param Value $value
     *
     * @return FavoriteGames[] Returns an array of Films objects
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

    // /**
    //  * @return FavoriteGame[] Returns an array of FavoriteGame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FavoriteGame
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Save record.
     *
     * @param FavoriteGames $favorite Favoritygame entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(FavoriteGames $favorite): void
    {
        $this->_em->persist($favorite);
        $this->_em->flush($favorite);
    }

    /**
     * Delete record.
     *
     * @param FavoriteGames $favorite FavoriteMovies entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(FavoriteGames $favorite)
    {
        $this->_em->remove($favorite);
        $this->_em->flush($favorite);
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
        return $queryBuilder ?: $this->createQueryBuilder('favorite_games');
    }
}
