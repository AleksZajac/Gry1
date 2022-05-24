<?php
/**
 * Hello controller.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class HelloController.
 */
class HelloController
{
    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    public function index(): Response
    {
        return new Response('Hello World!');
    }
}
