<?php
/**
 * Hello controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class HelloController.
 */
class HomeController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response HTTP response
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="home_index",
     * )
     */
    public function index(): Response
    {
        return $this->render(
            'home/index.html.twig',
        );
    }
}
