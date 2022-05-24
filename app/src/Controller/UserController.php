<?php
/**
 * UserController.
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Entity\UsersProfile;
use App\Form\ChangeUserPasswordType;
use App\Form\Model\ChangePassword;
use App\Form\UserProfileType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\UserProfileRepository;
use App\Service\UserProfileService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserController.
 *
 *@Route("/profile",)
 */
class UserController extends AbstractController
{
    /**
     * Category service.
     *
     * @var UserProfileService
     */
    private $profileService;

    /**
     * CategoryController constructor.
     *
     * @param UserProfileService $profileService UserProfile service
     */
    public function __construct(UserProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Index action.
     *
     * @Route(
     *     "/",
     *     name="profile_index",
     *     )
     *
     * @return Response HTTP response
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED",
     *      message="You can not enty")
     */
    public function index(): Response
    {
        $usersdata = $this->getUser()->getUserProfile();

        return $this->render(
            'userdata/index.html.twig',
            ['userdata' => $usersdata]
        );
    }

    /**
     * Edit Data.
     *
     * @param UserProfileRepository $repository UsersProfile repository
     * @param Request                $request    HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/editData",
     *     methods={"GET", "PUT"},
     *     name="data_edit",
     * )

     */
    public function editData(UserProfileRepository $repository, Request $request): Response
    {
        $userprofile = $this->getUser()->getUserprofile();
        $form = $this->createForm(UserProfileType::class, $userprofile, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->profileService->save($userprofile);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('profile_index');
        }

        return $this->render(
            'userdata/editData.html.twig',
            [
                'form' => $form->createView(),
                'userprofile' => $userprofile,
            ]
        );
    }

    /**
     * Change password action.
     *
     * @param Request        $request    HTTP request
     * @param UserRepository $repository User repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *      "/change_pwd",
     *      name="change_password",
     *      methods={"GET", "PUT"},
     * )
     *
     */
    public function changeUserPassword(Request $request, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder, Security $security)
    {
        $user = $security->getUser();
        $changepwd = new ChangePassword();
        $form = $this->createForm(ChangeUserPasswordType::class, $changepwd, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $changepwd->getPassword());
            $user = $this->getUser();
            $user->setPassword($password);
            $repository->save($user);
            $this->addFlash('success', 'success.changepassword');

            return $this->redirectToRoute('profile_index');
        }

        return $this->render(
            'userdata/change_pwd.html.twig',
            ['form' => $form->createView()]
        );
    }
}
