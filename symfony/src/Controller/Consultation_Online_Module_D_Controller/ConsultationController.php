<?php

namespace App\Consultation_Online_Module_D_Entity\Controller;

use App\Consultation_Online_Module_D_Entity\Entity\Consultation;
use App\Consultation_Online_Module_D_Forums\Form\ConsultationType;
use App\Consultation_Online_Module_D_Repository\Repository\ConsultationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/consultation')]
class ConsultationController extends AbstractController
{
    #[Route('/', name: 'app_consultation_index', methods: ['GET'])]
    public function index(Request $request, ConsultationRepository $consultationRepository): Response
    {
        // Récupérer les paramètres de recherche et tri
        $search = $request->query->get('search', '');
        $sort = $request->query->get('sort', 'dateDebut');
        $order = $request->query->get('order', 'DESC');

        $queryBuilder = $consultationRepository->createQueryBuilder('c')
            ->leftJoin('c.patient', 'p')
            ->leftJoin('c.medecin', 'm');

        // FONCTIONNALITÉ AVANCÉE : Recherche
        if ($search) {
            $queryBuilder
                ->where('p.nom LIKE :search OR p.prenom LIKE :search')
                ->orWhere('m.nom LIKE :search OR m.prenom LIKE :search')
                ->orWhere('c.diagnostic LIKE :search')
                ->orWhere('c.motif LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // FONCTIONNALITÉ AVANCÉE : Tri
        $validSortFields = ['dateDebut', 'statut', 'type', 'id'];
        if (in_array($sort, $validSortFields)) {
            $queryBuilder->orderBy('c.' . $sort, $order === 'ASC' ? 'ASC' : 'DESC');
        }

        $consultations = $queryBuilder->getQuery()->getResult();

        return $this->render('consultation/index.html.twig', [
            'consultations' => $consultations,
            'search' => $search,
            'sort' => $sort,
            'order' => $order,
        ]);
    }

    #[Route('/new', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $consultation = new Consultation();
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // VALIDATION CÔTÉ SERVEUR (pas de JavaScript!)
            $errors = $validator->validate($consultation);
            
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->render('consultation/new.html.twig', [
                    'consultation' => $consultation,
                    'form' => $form,
                ]);
            }

            // RÈGLES MÉTIER PERSONNALISÉES
            if ($consultation->getDateDebut() < new \DateTime()) {
                $this->addFlash('error', 'La date de consultation ne peut pas être dans le passé');
                return $this->render('consultation/new.html.twig', [
                    'consultation' => $consultation,
                    'form' => $form,
                ]);
            }

            // Initialiser les valeurs par défaut
            $consultation->setStatut('PLANIFIEE');
            $consultation->setPayee(false);

            $entityManager->persist($consultation);
            $entityManager->flush();

            $this->addFlash('success', 'Consultation créée avec succès !');
            return $this->redirectToRoute('app_consultation_index');
        }

        return $this->render('consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_show', methods: ['GET'])]
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // VALIDATION SERVEUR
            $errors = $validator->validate($consultation);
            
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->render('consultation/edit.html.twig', [
                    'consultation' => $consultation,
                    'form' => $form,
                ]);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Consultation modifiée avec succès !');
            return $this->redirectToRoute('app_consultation_show', ['id' => $consultation->getId()]);
        }

        return $this->render('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
            $this->addFlash('success', 'Consultation supprimée avec succès !');
        }

        return $this->redirectToRoute('app_consultation_index');
    }

    // Actions supplémentaires
    #[Route('/{id}/demarrer', name: 'app_consultation_demarrer', methods: ['POST'])]
    public function demarrer(Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $consultation->setStatut('EN_COURS');
        $consultation->setDateDebut(new \DateTime());
        $entityManager->flush();

        $this->addFlash('success', 'Consultation démarrée');
        return $this->redirectToRoute('app_consultation_show', ['id' => $consultation->getId()]);
    }

    #[Route('/{id}/terminer', name: 'app_consultation_terminer', methods: ['POST'])]
    public function terminer(Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $consultation->setStatut('TERMINEE');
        $consultation->setDateFin(new \DateTime());
        
        // Calculer durée
        if ($consultation->getDateDebut()) {
            $duree = $consultation->getDateDebut()->diff($consultation->getDateFin());
            $consultation->setDureeMinutes($duree->i + ($duree->h * 60));
        }
        
        $entityManager->flush();

        $this->addFlash('success', 'Consultation terminée');
        return $this->redirectToRoute('app_consultation_show', ['id' => $consultation->getId()]);
    }
}