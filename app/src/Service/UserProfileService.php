<?php
/**
 * UserProfileService.
 */

namespace App\Service;

use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserProfileService.
 */
class UserProfileService
{
    /**
     * UserProfile repository.
     *
     * @var UserProfileRepository
     */
    private $profileRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * UserProfileService constructor.
     *
     * @param UserProfileRepository $profileRepository UserProfile repository
     * @param PaginatorInterface     $paginator         Paginator
     */
    public function __construct(UserProfileRepository $profileRepository, PaginatorInterface $paginator)
    {
        $this->profileRepository = $profileRepository;
        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserProfile $profile): void
    {
        $this->profileRepository->save($profile);
    }
}
