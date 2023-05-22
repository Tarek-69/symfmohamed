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

    #[Route('/{id}/edit', name: 'app_animals_edit', methods: ['PUT'])]
    public function edit(Request $request, Animals $animal, AnimalsRepository $animalsRepository): Response
{
    $animalData = json_decode($request->getContent(), true);

    if (isset($animalData['animal'])) {
        $animalData = $animalData['animal'];

        // On met à jour les attributs de l'objet $animal avec les nouvelles données
        if (isset($animalData['name'])) {
            $animal->setName($animalData['name']);
        }

        if (isset($animalData['size'])) {
            $animal->setSize($animalData['size']);
        }

        if (isset($animalData['lifetime'])) {
            $animal->setLifetime($animalData['lifetime']);
        }

        if (isset($animalData['phone'])) {
            $animal->setPhone($animalData['phone']);
        }

        if (isset($animalData['martialArt'])) {
            $animal->setMartialArt($animalData['martialArt']);
        }

        if (isset($animalData['country'])) {
            $countryData = $animalData['country'];
            if (isset($countryData['id'])) {
                $country = $animalsRepository->findCountryById($countryData['id']);
                if ($country) {
                    $animal->setCountry($country);
                }
            }
        }

        $animalsRepository->save($animal, true);

        return $this->json([
            'animal' => $animal,
        ]);
    }

    return $this->json([
        'error' => 'Invalid JSON format',
    ], Response::HTTP_BAD_REQUEST);
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
