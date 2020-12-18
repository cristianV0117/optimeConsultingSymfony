<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{

    /**
     * @Route("/", methods={"GET"})
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Products::class);
        $query = $repository->createQueryBuilder('p')
           ->select('p.id', 'p.code', 'p.name', 'p.brand', 'p.price', 'ca.name as nameCategorie')
           ->innerJoin(Categories::class, 'ca', 'WITH', 'ca.id = p.categories')
           ->where('ca.active = :active')
           ->setParameter('active', true)
           ->getQuery();
        $records = $query->execute();
        return $this->render('home/index.html.twig', [
            "records" => $records
        ]);
    }

}