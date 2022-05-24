<?php
/**
 * FavoriteGame service.
 */

namespace App\Service;

use App\Entity\FavoriteGames;
use App\Repository\FavoriteGamesRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class FavoriteService.
 */
class FavoriteService
{
    /**
     * FavoriteGamesRepository
     *
     * @var FavoriteGamesRepository
     */
    private $favoriteGamesRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * FavoriteService constructor.
     *
     * @param FavoriteGamesRepository $favoriteGamesRepository FavoriteGame repository
     * @param PaginatorInterface       $paginator                Paginator
     */
    public function __construct(FavoriteGamesRepository $favoriteGamesRepository, PaginatorInterface $paginator)
    {
        $this->favoriteGamesRepository = $favoriteGamesRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, int $id): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->favoriteGamesRepository->queryAll($id),
            $page,
            FavoriteGamesRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save category.
     *
     * @param FavoriteGames $favorite FavoriteGame entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(FavoriteGames $favorite): void
    {
        $this->favoriteGamesRepository->save($favorite);
    }

    /**
     * Delete.
     *
     * @param FavoriteGames $favorite Favorite entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(FavoriteGames $favorite): void
    {
        $this->favoriteGamesRepository->delete($favorite);
    }

    /**
     * Delete.
     *
     * @param FavoriteGames $favorite Favorite entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deletegame(FavoriteGames $favorite): void
    {
        $this->favoriteGamesRepository->delete($favorite);
    }

    /**
     * Find favorite by Id.
     *
     * @param int $id Tag Id
     *
     * @return FavoriteGames|null FavoriteGame entity
     */
    public function findOneById(int $id): ?FavoriteGames
    {
        return $this->favoriteGamesRepository->findOneById($id);
    }

    /**
     * @return FavoriteGames[]
     */
    public function findByuser(int $user)
    {
        return $this->favoriteGamesRepository->findBy(['user' => $user]);
    }

    /**
     * @return FavoriteGames|null
     */
    public function findOneByuser(int $user)
    {
        return $this->favoriteGamesRepository->findOneBy(['user' => $user]);
    }
}
