<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\AdSearchType;
use App\Form\GameType;
use App\Repository\CommentsRepository;
use App\Repository\GameRepository;
use App\Service\CategoryService;
use App\Service\GameService;
use App\Service\TagService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



/**
 * Class GamesController.
 *
 * @Route("/games")
 */
class GamesController extends AbstractController
{
    /**
     * Game service.
     *
     * @var GameService
     */
    private $gameService;
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
     * CategoryController constructor.
     *
     * @param GameService $gameService Category service
     */
    public function __construct(GameService $gameService,CategoryService $categoryService, TagService $tagService)
    {
        $this->gameService = $gameService;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Index action.
     *
     * @param Request            $request        HTTP Request
     * @param PaginatorInterface $paginator      Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="games_index",
     * )
     */
    public function index(Request $request, GameRepository $repository, PaginatorInterface $paginator): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        $pagination = $this->gameService->createPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );
        $category = $this->categoryService->allCategory();
        $tag = $this->tagService->allTag();
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pagination = null;
            $title = $form->getData();
            $pagination = $paginator->paginate(
                $repository->queryByTitle($title),
                $request->query->getInt('page', 1),
                Game::NUMBER_OF_ITEMS
            );

            return $this->render(
                'game/searchView.html.twig',
                ['pagination' => $pagination]
            );
        }

        return $this->render(
            'game/index.html.twig',
            ['pagination' => $pagination,
                'category' => $category,
                'tag' => $tag,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Show action.
     *
     * @param Game $game Game entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="game_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Game $game,CommentsRepository $commentsRepository, Request $request, $id): Response
    {
        $comments = $game->getComments();
        return $this->render(
            'game/show.html.twig',
            ['game' => $game,
             'comments' => $comments]
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
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="game_new",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, GameRepository $repository): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->gameService->save($game);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('games_index');
        }

        return $this->render(
            'game/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Game $game
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="game_edit",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Game $game): Response
    {
        $form = $this->createForm(GameType::class, $game, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->gameService->save($game);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('games_index');
        }

        return $this->render(
            'game/edit.html.twig',
            [
                'form' => $form->createView(),
                'game' => $game,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Game   $game    Game entity
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
     *     name="game_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Game $game): Response
    {
        $form = $this->createForm(FormType::class, $game, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->gameService->delete($game);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('games_index');
        }

        return $this->render(
            'game/delete.html.twig',
            [
                'form' => $form->createView(),
                'game' => $game,
            ]
        );
    }
    /**
     * Advanced Search action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/adSearch",
     *     methods={"GET", "POST"},
     *     name="games_search",
     * )
     */
    public function adSearch(Request $request): Response
    {
        $games = new Game();
        $form = $this->createForm(AdSearchType::class, $games);
        $form->handleRequest($request);
        $filters = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $filters['category_id'] = $form->getData()->getCategory();
            $pagination = $this->gameService->createPaginatedList(
                $request->query->getInt('page', 1),
                $filters
            );

            return $this->render(
                'game/searchView.html.twig',
                ['pagination' => $pagination, 'filters' => $filters]
            );
        }

        return $this->render(
            'game/adSearch.html.twig',
            ['form' => $form->createView()]
        );
    }
}