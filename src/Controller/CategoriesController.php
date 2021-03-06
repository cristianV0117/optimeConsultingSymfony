<?php
/**
 * @author Cristian Camilo Vasquez Osorio 16/12/20
 * Clase de Categorias
*/
namespace App\Controller;


use App\Entity\Categories;
use App\Core\Validator as v;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/categories", methods={"GET"}, name="categories")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categories", methods={"POST"})
     */
    public function store(Request $request): JsonResponse
    {
        $post = json_decode($request->getContent());
        $validateCode  = (new v())->validate($post->code)->specialCharacters()->failed();
        $validateName  = (new v())->validate($post->name)->minMaxLength(2, 100)->failed();

        if ($validateCode || $validateName) {
            return new JsonResponse([
                "error" => true,
                "message" => "Por favor valida correctamente tus datos"
            ]);
        }
        if ($this->categoriesRecordExist($post->code, $post->name)) {
            return new JsonResponse([
                "error" => true,
                "message" => "El registro ya existe"
            ]);
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = new Categories();
        $categorie->setCode($post->code);
        $categorie->setName($post->name);
        $categorie->setDescription($post->description);
        $categorie->setActive($post->active);
        $entityManager->persist($categorie);
        $entityManager->flush();
        return new JsonResponse([
            "error"   => false,
            "message" => "Se ha registrado correctamente"
        ]);
    }

    /**
     * @Route("/categories/update/{id}", methods={"GET"})
     */
    public function edit(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        return $this->render('categories/update.html.twig', [
            "categorie" => $categorie
        ]);
    }

    /**
     * @Route("/categories/{id}", methods={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $post = json_decode($request->getContent());
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        $categorie->setCode($post->code);
        $categorie->setName($post->name);
        $categorie->setDescription($post->description);
        $entityManager->flush();
        return new JsonResponse([
            "error"   => false,
            "message" => "Se ha actualizado con exito"
        ]);
    }

    /**
     * @Route("/categories/disable/{id}", methods={"PUT"})
     */
    public function disable(int $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        $categorie->setActive(false);
        $entityManager->flush();
        return new JsonResponse([
            "error"   => false,
            "message" => "Se ha actualizado correctamente"
        ]);
    }

    /**
     * @Route("/categories/enable/{id}", methods={"PUT"})
     */
    public function enable(int $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = $entityManager->getRepository(Categories::class)->find($id);
        $categorie->setActive(true);
        $entityManager->flush();
        return new JsonResponse([
            "error"   => false,
            "message" => "Se ha actualizado correctamente"
        ]);
    }

    /**
     * @Route("/categories/select", methods={"GET"})
     */
    public function show(): JsonResponse
    {
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $query = $repository->createQueryBuilder('c')
            ->select('c.id', 'c.name')
            ->where('c.active = :active')
            ->setParameter('active', true)
            ->getQuery();
        $categories = $query->execute();
        return new JsonResponse([
            "error"   => false,
            "message" => $categories
        ]);
    }

    private function categoriesRecordExist($code, $name)
    {
        $repository = $this->getDoctrine()->getRepository(Categories::class);
        $query = $repository->createQueryBuilder('c')
            ->select('c.id')
            ->where('c.code = :code')
            ->orWhere('c.name = :name')
            ->setParameter('code', $code)
            ->setParameter('name', $name)
            ->getQuery();
        $categorie = $query->execute();
        return (!empty($categorie)) ? true : false;
    }
}
