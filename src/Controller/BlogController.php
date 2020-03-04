<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_list", requirements={"page"="\d+"})
     */
    public function index($page = 1)
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route(
     *   "/blog/{username}/{slug}", 
     *   name="blog_post", 
     *   requirements={"username"="\w+","slug"="\w+"}
     * )
     */
    public function detail($username, $slug)
    {
        return $this->render('blog/detail.html.twig', [
            'controller_name' => 'BlogDetailController',
        ]);
    }
}
