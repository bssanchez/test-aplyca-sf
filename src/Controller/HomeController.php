<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $lastThreePosts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->getLastOrdered();

        return $this->render('home/index.html.twig', [
            'title' => 'MY DUMMY BLOG',
            'posts' => $lastThreePosts
        ]);
    }
}
