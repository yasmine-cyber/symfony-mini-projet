<?php

   namespace App\Controller;

   use App\Repository\ParticipantRepository;
   use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
   use Symfony\Component\HttpFoundation\Response;
   use Symfony\Component\Routing\Attribute\Route;
   use Symfony\Component\Security\Http\Attribute\IsGranted;

   #[Route('/admin')]
   class AdminController extends AbstractController
   {
       #[Route('/', name: 'admin_dashboard')]
       public function index(ParticipantRepository $participantRepository): Response
       {
           $this->denyAccessUnlessGranted('ROLE_ADMIN');

           $user = $this->getUser();
           $roles = $user ? $user->getRoles() : [];
           $this->addFlash('info', 'Rôles actuels : ' . implode(', ', $roles));

           $participants = $participantRepository->findAll();

           return $this->render('admin/index.html.twig', [
               'participants' => $participants,
         ]);
       }
   }