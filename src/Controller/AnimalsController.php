<?php

namespace App\Controller;

use App\Entity\Animals;
use PHPUnit\TextUI\Help;
use App\Form\AnimalsType;
use App\Repository\AnimalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/animals')]
class AnimalsController extends AbstractController
{
    #[Route('/', name: 'app_animals_index', methods: ['GET'])]
    public function index(AnimalsRepository $animalsRepository): Response
    {
        return $this->json([
            'animals' => $animalsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_animals_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnimalsRepository $animalsRepository, EntityManagerInterface $entityManager): Response
    {
        $animalData = json_decode($request->getContent(), true); // true pour tableau associatif
        $country = $entityManager->getRepository('App\Entity\Countries')->find($animalData['country']); // on récupère le pays
        $animal = new Animals(); // on instancie un nouvel animal
        $animal->setName($animalData['name']) // on hydrate l'objet
        ->setSize($animalData['size'])
        ->setLifetime( $animalData['lifetime']) 
        ->setPhone($animalData['phone']) 
        ->setMartialArt($animalData['martialArt']) 
        ->setCountry($country); 
        $entityManager->persist($animal); // on persiste l'objet
        $entityManager->flush(); // on flush

        
        return $this->json([ // on retourne un json
             $animal,
        ], Response::HTTP_CREATED); // on retourne le code 201
    }

    #[Route('/{id}', name: 'app_animals_show', methods: ['GET'])] 
    public function show(Animals $animal): Response // on récupère l'animal
    {
        // return $this->render('animals/show.html.twig', [
        //     'animal' => $animal,
        // ]);

        return $this->json([ // on retourne un json avec l'animal
            'animal' => $animal,  
        ]);
    }

    #[Route('/{id}/edit', name: 'app_animals_edit', methods: ['PUT', 'PATCH'])]
    public function edit(Request $request, Animals $animal, AnimalsRepository $animalsRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(AnimalsType::class, $animal);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $animalsRepository->save($animal, true);
            //return $this->redirectToRoute('app_animals_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->json([
            'animal' => $animal,
            'message' => "L'animal a été modifié"
        ], Response::HTTP_OK);

        // return $this->renderForm('animals/edit.html.twig', [
        //     'animal' => $animal,
        //     'form' => $form,
        // ]);
    }

   #[Route('/{id}', name: 'app_animals_delete', methods: ['DELETE'])]
   public function delete(Animals $animal, AnimalsRepository $animalsRepository): Response
    {
         $animalsRepository->remove($animal, true);
    
         return $this->json(['message' => 'Objet supprimé'], Response::HTTP_OK);
    }

    #[Route('/search', name: 'app_animals_search', methods: ['GET'])] // on crée une route pour la recherche
    public function search(Request $request, AnimalsRepository $animalsRepository): Response // on récupère la requête et le repository
    {
        $search = $request->query->get('search'); // on récupère le paramètre search
        $animals = $animalsRepository->search($search); // on récupère les animaux correspondants à la recherche
        return $this->json([
            'animals' => $animals,
        ]);
    }

   

}
