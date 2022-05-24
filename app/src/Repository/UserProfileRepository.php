<?php
/**
 * UserProfile Repository.
 */

namespace App\Repository;

use App\Entity\UserProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class UserProfileRepository.
 *
 * @method UserProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfile[]    findAll()
 * @method UserProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfileRepository extends ServiceEntityRepository
{
    /**
     * UsersProfileRepository constructor.
     */
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfile::class);
    }

    /**
     * Save.
     *
     * @param UserProfile $userprofile UsersProfile entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserProfile $userprofile): void
    {
        $this->_em->persist($userprofile);
        $this->_em->flush($userprofile);
    }
}
