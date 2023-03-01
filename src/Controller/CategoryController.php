<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", name="app_category_list", methods={"GET"})
     */
    public function list(CategoryRepository $categoryRepository): Response
    {
        //renvoie un tableau d'objets Category
        return $this->json($categoryRepository->findAll(), 200, [], ['groups' => 'category:list']);
    }

    /**
     * @Route("", name="app_category_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            //place le contenu de la requete dans un objet Category
            $category = $serializer->deserialize($request->getContent(), Category::class, 'json');
            $errors = $validator->validate($category);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $em->persist($category);
            $em->flush();
            return $this->json($category, 201, [], ['groups' => ['category:list', 'category:read']]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", name="app_category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        //renvoie un objet Category
        return $this->json($category, 200, [], ['groups' => ['category:list', 'category:read']]);
    }

    /**
     * @Route("/{id}", name="app_category_edit", methods={"PUT"})
     */
    public function edit(Request $request, Category $category, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            $serializer->deserialize($request->getContent(), Category::class, 'json', ['object_to_populate' => $category]);
            $errors = $validator->validate($category);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $em->persist($category);
            $em->flush();
            return $this->json($category, 200, [], ['groups' => ['category:list', 'category:read']]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", name="app_category_delete", methods={"DELETE"})
     */
    public function delete(Category $category, CategoryRepository $categoryRepository): Response
    {
        //supprime un objet Category
        $categoryRepository->remove($category);
        return $this->json(['message' => 'Categorie supprimée'], 200);
    }
}
