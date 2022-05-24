<?php
/**
 * CommentsController.
 */

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Game;
use App\Form\CommentsType;
use App\Repository\CommentsRepository;
use App\Service\CommentsService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentsController.
 *
 * @Route("/comments")
 */
class CommentsController extends AbstractController
{
    /**
     * Comments service.
     *
     * @var CommentsService
     */
    private $commentsService;

    /**
     * Comments Controller constructor.
     */
    public function __construct(CommentsService $commentsService)
    {
        $this->commentsService = $commentsService;
    }

    /**
     *
     * Add comment action.
     *
     * @param $id
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/add_comment/{id}",
     *      methods={"GET", "POST"},
     *     name="add_comment",
     * )
     * @IsGranted("ROLE_USER")
     */
    public function addcommentForm(Request $request, CommentsRepository $repository, $id,ManagerRegistry $doctrine): Response
    {
        $comment = new Comments();
        $entityManager = $doctrine->getManager();
        $game = $entityManager->getRepository(Game::class)->find($id);
        $comment->setGames($game);
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser()->getUserprofile();
            $comment->setUser($user);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $repository->save($comment);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('games_index');
        }

        return $this->render(
            'comments/add_comment.html.twig',
            ['form' => $form->createView(),
                'id' => $id,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request            $request    HTTP request
     * @param Comments           $comments   Comments entity
     * @param CommentsRepository $repository Comments repository
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
     *     name="comment_delete",
     * )
     */
    public function delete(Request $request, Comments $comments, CommentsRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $comments, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentsService->delete($comments);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('games_index');
        }

        return $this->render(
            'comments/delete.html.twig',
            [
                'form' => $form->createView(),
                'comments' => $comments,
            ]
        );
    }
}
