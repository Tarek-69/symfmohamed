<?php

namespace App\Controller;

use App\Entity\Countries;
use App\Form\CountriesType;
use App\Repository\CountriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/countries')]
class CountriesController extends AbstractController
{
    #[Route('/', name: 'app_countries_index', methods: ['GET'])]
    public function index(CountriesRepository $countriesRepository): Response
    {
        return $this->render('countries/index.html.twig', [
            'countries' => $countriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_countries_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CountriesRepository $countriesRepository): Response
    {
        $country = new Countries();
        $form = $this->createForm(CountriesType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countriesRepository->save($country, true);

            return $this->redirectToRoute('app_countries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('countries/new.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_countries_show', methods: ['GET'])]
    public function show(Countries $country): Response
    {
        return $this->render('countries/show.html.twig', [
            'country' => $country,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_countries_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Countries $country, CountriesRepository $countriesRepository): Response
    {
        $form = $this->createForm(CountriesType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $countriesRepository->save($country, true);

            return $this->redirectToRoute('app_countries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('countries/edit.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_countries_delete', methods: ['POST'])]
    public function delete(Request $request, Countries $country, CountriesRepository $countriesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->request->get('_token'))) {
            $countriesRepository->remove($country, true);
        }

        return $this->redirectToRoute('app_countries_index', [], Response::HTTP_SEE_OTHER);
    }
   

}