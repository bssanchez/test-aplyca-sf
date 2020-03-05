<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $message = ['text' => '', 'class' => ''];

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $contact->setSendDate(new \DateTime('now'));
                $contact = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();

                $message = ['text' => 'Datos enviados satifactoriamente', 'class' => 'alert-success'];
                
                unset($contact, $form);
                $contact = new Contact();
                $form = $this->createForm(ContactType::class, $contact);
            } else {
                $message = ['text' => 'Uno o más campos son inválidos, por favor revise e intente nuevamente', 'class' => 'alert-danger'];
            }
        }

        return $this->render('contact/index.html.twig', [
            'title' => 'CONTÁCTANOS',
            'form' => $form->createView(),
            'message' => $message
        ]);
    }
}
