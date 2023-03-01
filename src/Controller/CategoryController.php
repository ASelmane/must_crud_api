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
        return $this->json($categoryRepository->findAll(), 200);
    }

    /**
     * @Route("", name="app_category_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            $category = $serializer->deserialize($request->getContent(), Category::class, 'json');
            $errors = $validator->validate($category);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $em->persist($category);
            $em->flush();
            return $this->json($category, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", name="app_category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->json($category, 200);
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
            return $this->json($category, 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", name="app_category_delete", methods={"DELETE"})
     */
    public function delete(Category $category, CategoryRepository $categoryRepository): Response
    {
        $categoryRepository->remove($category);
        return $this->json(['message' => 'Marque supprimée'], 200);
    }
}
