<?php
/**
 * GAme service.
 */

namespace App\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class GameService.
 */
class GameService
{
    /**
     * Category service.
     *
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;
    /**
     * Game repository.
     *
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * FilmsService constructor.
     *
     * @param GameRepository     $gameRepository Gamerepository
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(GameRepository $gameRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->gameRepository = $gameRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Create paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters array
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->gameRepository->queryAll($filters),
            $page,
            GameRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save game.
     *
     * @param Game $game Game entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Game $game): void
    {
        $this->gameRepository->save($game);
    }
    /**
     * Delete game.
     *
     * @param Game $game Game entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Game $game): void
    {
        $this->gameRepository->delete($game);
    }
    /**
     * Prepare filters for the tasks list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $category = $this->categoryService->findOneById(
                $filters['category_id']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (isset($filters['tag_id']) && is_numeric($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }
        if (isset($filters['mingamers'])) {
            $resultFilters['mingamers'] = $filters['mingamers'];
        }
        if (isset($filters['maxgamers'])) {
            $resultFilters['maxgamers'] = $filters['maxgamers'];
        }

        return $resultFilters;
    }
    /**
     * Show game.
     *
     * @param int $id Id user
     *
     * @return Game game
     */
    public function showGame(int $id)
    {
        return $this->gameRepository->find($id);
    }
}
