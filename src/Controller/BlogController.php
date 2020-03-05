<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Post;
use App\Form\BlogType;

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
     * @Route(
     *   "/edit/{post_id}", 
     *   name="blog_post", 
     *   requirements={"id"="\d+"}
     * )
     */
    public function editPost(Request $request, $post_id)
    {
        $message = ['text' => '', 'class' => ''];
        $user = $this->getUser();

        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBy(['id' => $post_id, 'autor' => $user->getId()]);

        if (is_null($post)) {
            return $this->redirect($this->generateUrl('blog_list'));
        }

        $form = $this->createForm(BlogType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $imageFile = $form->get('image')->getData();
                $safeSlug = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $form->get('title')->getData());

                $post->setPublishedDate(new \DateTime('now'));
                $post->setSlug($safeSlug);

                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('post_fi_directory'),
                            $newFilename
                        );

                        $post->setFeaturedImage($newFilename);

                        return $this->redirect($this->generateUrl('blog_edit', ['post_id' => $post->getId()]));
                    } catch (FileException $e) {
                        $message = ['text' => 'No se pudo cargar la imágen, por favor intente nuevamente', 'class' => 'alert-danger'];
                    }
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                $message = ['text' => 'Post actualizado satisfactoriamente', 'class' => 'alert-success'];
            } else {
                $message = ['text' => 'Uno o más campos son inválidos, por favor revise e intente nuevamente', 'class' => 'alert-danger'];
            }
        }

        return $this->render('blog/form.html.twig', [
            'title' => 'EDITAR PUBLICACIÓN',
            'message' => $message,
            'form' => $form->createView(),
            'post' => $post
        ]);
    }

    /**
     * @Route("/new", name="new_post")
     */
    public function newPost(Request $request)
    {
        $message = ['text' => '', 'class' => ''];

        $post = new Post();
        $form = $this->createForm(BlogType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $imageFile = $form->get('image')->getData();
                $safeSlug = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $form->get('title')->getData());
                $author = $this->getUser();

                $post->setPublishedDate(new \DateTime('now'));
                $post->setFeaturedImage('post-image-dummy.png');
                $post->setSlug($safeSlug);
                $post->setAutor($author);

                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('post_fi_directory'),
                            $newFilename
                        );

                        $post->setFeaturedImage($newFilename);
                    } catch (FileException $e) {
                        $message = ['text' => 'No se pudo cargar la imágen, por favor intente nuevamente', 'class' => 'alert-danger'];
                    }
                }

                if (empty($message['text'])) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($post);
                    $entityManager->flush();

                    return $this->redirect($this->generateUrl('blog_edit', ['post_id' => $post->getId()]));
                }
            } else {
                $message = ['text' => 'Uno o más campos son inválidos, por favor revise e intente nuevamente', 'class' => 'alert-danger'];
            }
        }

        return $this->render('blog/form.html.twig', [
            'title' => 'CREAR PUBLICACIÓN',
            'message' => $message,
            'form' => $form->createView()
        ]);
    }
}
