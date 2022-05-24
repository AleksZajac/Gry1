<?php
/**
 * CommentsService.
 */

namespace App\Service;


use App\Entity\Comments;
use App\Repository\CommentsRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CommentsService.
 */
class CommentsService
{
    /**
     * Comments repository.
     *
     * @var CommentsRepository
     */
    private $commentsRepository;
    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Comments constructor.
     */
    public function __construct(CommentsRepository $commentsRepository, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        $this->commentsRepository = $commentsRepository;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentsRepository->findAll(),
            $page,
            CommentsRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Delete category.
     *
     * @param Comments $comments Categories entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Comments $comments): void
    {
        $this->commentsRepository->delete($comments);
    }

    /**
     * Save announcement.
     *
     * @param Comments $comments Comments entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Comments $comments): void
    {
        $this->commentsRepository->save($comments);
    }
}
