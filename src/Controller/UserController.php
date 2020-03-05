<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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
                if ($form->get('password')->getData() !== $request->request->get('password_repeat')) {
                    $message = ['text' => 'Las contraseñas no coinciden', 'class' => 'alert-danger'];
                } else {
                    $username = explode('@', $form->get('email')->getData())[0];
                    $user = $form->getData();
                    $user->setPassword(
                        $this->passwordEncoder->encodePassword(
                            $user, 
                            $form->get('password')->getData()
                        )
                    );
                    $user->setRoles(['ROLE_USER']);
                    $user->setUsername($username);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $message = ['text' => 'Te has registrado correctamente, ya puedes iniciar sesión con tus credenciales', 'class' => 'alert-success'];

                    unset($user, $form);
                    $user = new User();
                    $form = $this->createForm(RegisterType::class, $user);
                }
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
