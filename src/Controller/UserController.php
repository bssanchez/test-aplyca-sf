<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $message = ['text' => '', 'class' => ''];
        // dump($user); die;
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $message = ['text' => 'Datos enviados satifactoriamente', 'class' => 'alert-success'];

                unset($user, $form);
                $user = new User();
                $form = $this->createForm(RegisterType::class, $user);
            } else {
                $message = ['text' => 'Uno o más campos son inválidos, por favor revise e intente nuevamente', 'class' => 'alert-danger'];
            }
        }

        return $this->render('security/signin.html.twig', [
            'title' => 'REGISTRO',
            'form' => $form->createView(),
            'message' => $message
        ]);
    }
}
