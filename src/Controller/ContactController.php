<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function SendEmail(Request $request, MailerInterface $mailer)
    {
        $transport = Transport::fromDsn('smtp://localhost:1025');
        $mailer = new Mailer($transport);
        $form = $this->createForm(ContactType::class);
        $contact=$form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
           // on cree le mail
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to('diaroun74@yahoo.fr')
                ->subject('Formulaire de contact')
                ->html($contact->get('message')->getData());
           // on envoie le mail
            $mailer->send($email);
           // on confirme l'envoi du mail
            $this->addFlash('success', 'Vore message a été envoyé avec succès');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
}