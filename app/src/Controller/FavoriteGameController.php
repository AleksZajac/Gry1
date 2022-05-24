<?php
/**
 * FavoriteGameController.
 */

namespace App\Controller;

use App\Entity\FavoriteGames;
use App\Entity\Game;
use App\Form\FavoriteGameType;
use App\Repository\FavoriteGamesRepository;
use App\Service\FavoriteService;
use App\Service\GameService;
use App\Service\UserService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FavoriteGameController.
 *
 * @Route("/favorite")
 *
 * @IsGranted("ROLE_USER")
 */
class FavoriteGameController extends AbstractController
{
    /**
     * Favorite service.
     *
     * @var FavoriteService
     */
    private $favoriteService;
    /**
     * Favorite service.
     *
     * @var GameService
     */
    private $gameService;
    /**
     * Favorite service.
     *
     * @var UserService
     */
    private $userService;
    /**
     * Favorite service.
     *
     * @var FavoriteGamesRepository
     */
    private $favoriteGamesRepository;

    /**
     * FavoriteMoviesController constructor.
     *
     * @param FavoriteService $favoriteService Favorite service
     */
    public function __construct(FavoriteService $favoriteService, GameService $gameService, UserService $userService, FavoriteGamesRepository $favoriteGamesRepository)
    {
        $this->favoriteService = $favoriteService;
        $this->gameService = $gameService;
        $this->userService = $userService;
        $this->favoriteGamesRepository = $favoriteGamesRepository;
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="favorite_index",
     * )
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $user = $this->getUser()->getId();
        $pagination = $this->favoriteService->createPaginatedList($page, $user);

        return $this->render(
            'favorite/index.html.twig',
            ['pagination' => $pagination,
            ]
        );
    }

    /**
     * New action.
     *
     * @param Request $request HTTP request*
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="favorite_new",
     * )
     */
    public function new(Request $request, Game $game, int $id): Response
    {
        $userr = $this->getUser()->getId();
        $thisuser = $this->getUser();
        //if ($this->favoriteMoviesRepository->findBy(['id_user' => $userr,'id_film' => $id])){
        //    $this->addFlash('success', 'message_call ready on list');

        //    return $this->redirectToRoute('favorite_index');
        //}
        if ($this->favoriteService->findByuser($userr)) {
            $favorite = $this->favoriteService->findOneByuser($userr);
        } else {
            $favorite = new FavoriteGames();
            $favorite->setUser($thisuser);
        }
        $game = $this->gameService->showGame($id);
        $user = $this->userService->showUser($userr);
        $form = $this->createForm(FavoriteGameType::class, $favorite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $favorite->addGame($game);
            $this->favoriteService->save($favorite);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('favorite_index');
        }

        return $this->render(
            'favorite/new.html.twig',
            ['form' => $form->createView(),
                'id' => $id, ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request                  $request            HTTP request
     * @param FavoriteGames           $favorite           Category entity
     * @param FavoriteGamesRepository $favoriteRepository Category repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="favorite_delete",
     * )
     * /**
     */
    public function delete(Request $request, FavoriteGames $favorite, ManagerRegistry $doctrine, Game $games, int $id): Response
    {

        $entityManager = $doctrine->getManager();
        $exiFav = $games->getFavoriteGames();
        $form = $this->createForm(FormType::class, $favorite, ['method' => 'DELETE']);
        $form->handleRequest($request);
        $games = $entityManager->getRepository(Game::class)->find($id);
        $user = $this->getUser();
        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $favoritemovie = $games->getFavoriteGames();
            $this->favoriteService->delete($favoritemovie);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('favorite_index');
        }

        return $this->render(
            'favorite/delete.html.twig',
            [
                'form' => $form->createView(),
                'favorite' => $favorite,
            ]
        );
    }
}
