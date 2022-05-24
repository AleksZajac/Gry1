<?php
/**
 * Type controller.
 */

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\CategoryRepository;
use App\Repository\TypeRepository;
use App\Service\CategoryService;
use App\Service\TypeService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController.
 *
 * @Route("/type")
 *
 */
class TypeController extends AbstractController
{
    /**
     * Type service.
     *
     * @var TypeService
     */
    private $typeService;
    /**
     * Category service.
     *
     * @var CategoryService
     */
    private $categoryService;
    /**
     * CategoryController constructor.
     *
     * @param TypeService $typeService Type service
     * @param CategoryService $categoryService Category service
     */
    public function __construct(TypeService $typeService, CategoryService $categoryService)
    {
        $this->typeService = $typeService;
        $this->categoryService = $categoryService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="type_index",
     * )
     */
    public function index(TypeRepository $repository, PaginatorInterface $paginator, Request $request,CategoryRepository $categoryRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->typeService->createPaginatedList($page);
        $pagination1 = $this->categoryService->createPaginatedList($page);
        return $this->render(
            'type/index.html.twig',
            ['pagination' => $pagination, 'pagination1' => $pagination1]
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
     *     name="type_new",
     * )
     */
    public function new(Request $request): Response
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->typeService->save($type);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'type/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Type $type Films entity*
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="type_edit",
     * )
     */
    public function edit(Request $request, Type $type, TypeRepository $repository): Response
    {
        $form = $this->createForm(TypeType::class, $type, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->typeService->save($type);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'type/edit.html.twig',
            [
                'form' => $form->createView(),
                'type' => $type,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Type $type Type entity
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
     *     name="type_delete",
     * )
     */
    public function delete(Request $request, Type $type): Response
    {
        if ($type->getGames()->count()) {
            $this->addFlash('warning', 'message_category_contains_tasks');

            return $this->redirectToRoute('type_index');
        }

        $form = $this->createForm(FormType::class, $type, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->typeService->delete($type);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('type_index');
        }

        return $this->render(
            'type/delete.html.twig',
            [
                'form' => $form->createView(),
                'type' => $type,
            ]
        );
    }
}
