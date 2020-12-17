<?php
/**
 * @author Cristian Camilo Vasquez Osorio 16/12/20
 * Clase de productos
*/
namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", methods={"GET"}, name="products")
     */
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
    
    /**
     * @Route("/products", methods={"POST"})
     */
    public function store(Request $request): JsonResponse
    {
        $post          = json_decode($request->getContent());
        $categories    = $this->getDoctrine()->getRepository(Categories::class)->find($post->categorie);
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = new Products();
        $categorie->setCode($post->code);
        $categorie->setName($post->name);
        $categorie->setDescription($post->description);
        $categorie->setBrand($post->brand);
        $categorie->setCategories($categories);
        $categorie->setPrice($post->price);
        $entityManager->persist($categorie);
        $entityManager->flush();
        return new JsonResponse([
            "error" => false,
            "message" => "Se ha registrado correctamente"
        ]);
    }

}
