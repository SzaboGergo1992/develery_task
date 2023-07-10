<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    public string  $errorMessage = 'asd';

    #[Route('/contact', name: 'app_contact')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(ContactMessageType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactMessage = $form->getData();
            $contactMessage->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d\TH:i:sP', date('Y-m-d\TH:i:sP')));

            $em->persist($contactMessage);
            $em->flush();

            $this->addFlash('success', 'Köszönjük szépen a kérdésedet.
Válaszunkkal hamarosan keresünk a megadott e-mail címen.');
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
            'errorMessage' => $this->errorMessage,
            'form' => $form->createView()
        ]);
    }

}
