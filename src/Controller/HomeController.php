<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\EventRepository;
use App\Repository\ProductRepository;
use App\Repository\GalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        PostRepository $postRepository,
        EventRepository $eventRepository,
        ProductRepository $productRepository,
        GalleryRepository $galleryRepository
    ): Response {
        // Récupérer les 5 derniers articles
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC'], 5);

        // Récupérer les 5 prochains événements
        $events = $eventRepository->findBy([], ['date' => 'ASC'], 5);

        // Récupérer les produits les plus récents
        $products = $productRepository->findBy([], ['createdAt' => 'DESC'], 5);

        // Récupérer les dernières galeries
        $randomGalleries = $galleryRepository->createQueryBuilder('g')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'events' => $events,
            'products' => $products,
            'galleries' => $randomGalleries,
        ]);
    }
}
