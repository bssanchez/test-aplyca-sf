<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_list", requirements={"page"="\d+"})
     */
    public function index($page = 1)
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)
            ->findAllWithAuthor();

        return $this->render('blog/index.html.twig', [
            'title' => 'BLOG',
            'posts' => $posts
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

    /**
     * @Route("/new", name="new_post")
     */
    public function newPost()
    {
        return $this->render('blog/index.html.twig', [
            'title' => 'CREAR PUBLICACIÓN'
        ]);
    }
}
