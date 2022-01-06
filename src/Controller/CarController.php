<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CarRepository;
use App\Repository\DescriptionRepository;
use Twig\Environment;
use App\Entity\Car;
use App\Entity\Description;

class CarController extends AbstractController
{
    private $twig;
    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }
    #[Route('/', name: 'homepage')]
    public function index(Environment $twig, CarRepository $carRepository)
    {
        return new Response($twig->render('car/index.html.twig', [
            'cars' => $carRepository->findAll(),
        ]));
    }

    #[Route('/car/{id}', name: 'car')]
    public function show(Request $request, Car $car, DescriptionRepository $descriptionRepository) {
        $offset = max(0,$request->query->getInt('offset', 0));
        $paginator = $descriptionRepository->getDescriptionPaginator($car, $offset);

        return new Response($this->twig->render('car/show.html.twig', [
            'car' => $car,
            'descriptions' => $paginator,
            'previous' => $offset-DescriptionRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator),$offset+DescriptionRepository::PAGINATOR_PER_PAGE),
        ]));
    }
}
