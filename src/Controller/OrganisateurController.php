<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementTypeForm;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organisateur')]
class OrganisateurController extends AbstractController
{
    #[Route('/', name: 'organisateur_dashboard')]
    public function dashboard(EvenementRepository $evenementRepository): Response
    {
        if (!$this->isGranted('ROLE_ORGANISATEUR')) {
            throw $this->createAccessDeniedException();
            
        }

        $organisateur = $this->getUser();
        $evenements = $evenementRepository->findBy(['organisateur' => $organisateur]);

        return $this->render('organisateur/dashboard.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/new', name: 'organisateur_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ORGANISATEUR')) {
            throw $this->createAccessDeniedException();
        }

        $evenement = new Evenement();
        $form = $this->createForm(EvenementTypeForm::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->setOrganisateur($this->getUser());
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('organisateur_dashboard');
        }

        return $this->render('organisateur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'organisateur_edit')]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ORGANISATEUR') || $evenement->getOrganisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(EvenementTypeForm::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('organisateur_dashboard');
        }

        return $this->render('organisateur/edit.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement,
        ]);
    }
}

/*
#[Route('/{id}/delete', name: 'organisateur_delete')]
public function delete(Evenement $evenement, EntityManagerInterface $em): Response
{
    if (!$this->isGranted('ROLE_ORGANISATEUR') || $evenement->getOrganisateur() !== $this->getUser()) {
        throw $this->createAccessDeniedException();
    }

    $em->remove($evenement);
    $em->flush();

    return $this->redirectToRoute('organisateur_dashboard');
}
*/
