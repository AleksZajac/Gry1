<?php
/**
 * Type service.
 */

namespace App\Service;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TypeService.
 */
class TypeService
{
    /**
     * Type repository.
     *
     * @var TypeRepository
     */
    private $typeRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * CategoryService constructor.
     *
     * @param TypeRepository $typeRepository Type repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(TypeRepository $typeRepository, PaginatorInterface $paginator)
    {
        $this->typeRepository = $typeRepository;
        $this->paginator = $paginator;
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
            $this->typeRepository->queryAll(),
            $page,
            TypeRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save category.
     *
     * @param Type $type Type entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Type $type): void
    {
        $this->typeRepository->save($type);
    }

    /**
     * Delete category.
     *
     * @param Type $type Type entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Type $type): void
    {
        $this->typeRepository->delete($type);
    }

    /**
     * Find category by Id.
     *
     * @param int $id Type Id
     *
     * @return Type|null Type entity
     */
    public function findOneById(int $id): ?Type
    {
        return $this->typeRepository->findOneById($id);
    }

    /**
     * @return Type[]
     */
    public function allType()
    {
        return $this->typeRepository->findAll();
    }
}
