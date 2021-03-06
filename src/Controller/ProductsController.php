<?php
/**
 * @author Cristian Camilo Vasquez Osorio 16/12/20
 * Clase de productos
*/
namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use App\Core\Validator as v;
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
        $products = $this->getDoctrine()->getRepository(Products::class)->findAll();
        return $this->render('products/index.html.twig', [
            'products' => $products,
        ]);
    }
    
    /**
     * @Route("/products", methods={"POST"})
     */
    public function store(Request $request): JsonResponse
    {
        $post = json_decode($request->getContent());
        $validateCode  = (new v())->validate($post->code)->specialCharacters()->minMaxLength(4, 10)->failed();
        $validateName  = (new v())->validate($post->name)->minMaxLength(4, 100)->failed();
        $validatePrice = (new v())->validate($post->price)->isNum()->isPositive()->failed(); 

        if ($validateCode || $validateName || $validatePrice) {
            return new JsonResponse([
                "error" => true,
                "message" => "Por favor valida correctamente tus datos"
            ]);
        }

        if ($this->productsRecordExist($post->code, $post->name)) {
            return new JsonResponse([
                "error" => true,
                "message" => "El registro ya existe"
            ]);
        }
        $categories    = $this->getDoctrine()->getRepository(Categories::class)->find($post->categorie);
        $entityManager = $this->getDoctrine()->getManager();
        $product = new Products();
        $product->setCode($post->code);
        $product->setName($post->name);
        $product->setDescription($post->description);
        $product->setBrand($post->brand);
        $product->setCategories($categories);
        $product->setPrice($post->price);
        $entityManager->persist($product);
        $entityManager->flush();
        return new JsonResponse([
            "error" => false,
            "message" => "Se ha registrado correctamente"
        ]);
    }

    /**
     * @Route("/products/update/{id}", methods={"GET"})
     */
    public function edit(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Products::class)->find($id);
        return $this->render('products/update.html.twig', [
            "product" => $product
        ]);
    }

    /**
     * @Route("/products/{id}", methods={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $post = json_decode($request->getContent());
        $categories    = $this->getDoctrine()->getRepository(Categories::class)->find($post->categorie);
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Products::class)->find($id);
        $product->setCode($post->code);
        $product->setName($post->name);
        $product->setDescription($post->description);
        $product->setBrand($post->brand);
        $product->setCategories($categories);
        $product->setPrice($post->price);
        $entityManager->flush();
        return new JsonResponse([
            "error"   => false,
            "message" => "Se ha actualizado con exito"
        ]);
    }

    /**
     * @Route("/products/{id}", methods={"DELETE"})
     */
    public function destroy(int $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Products::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        return new JsonResponse([
            "error"   => false,
            "message" => "Se ha eliminado correctamente"
        ]);
    }

    private function productsRecordExist($code, $name)
    {
        $repository = $this->getDoctrine()->getRepository(Products::class);
        $query = $repository->createQueryBuilder('c')
            ->select('c.id')
            ->where('c.code = :code')
            ->orWhere('c.name = :name')
            ->setParameter('code', $code)
            ->setParameter('name', $name)
            ->getQuery();
        $product = $query->execute();
        return (!empty($product)) ? true : false;
    }
}
