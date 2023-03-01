<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("", name="app_product_list", methods={"GET"})
     */
    public function list(ProductRepository $productRepository): Response
    {
        return $this->json($productRepository->findAll(), 200, [], ['groups' => ['product:read', 'brand:list', 'category:list']]);
    }

    /**
     * @Route("", name="app_product_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            // get brand value from request
            $content = json_decode($request->getContent(), true);
            $product = $serializer->deserialize($request->getContent(), Product::class, 'json');
            if (isset($content['brand'])) {
                $brand = $em->getRepository(Brand::class)->find($content['brand']);
                $product->setBrand($brand);
            }
            if (isset($content['category'])) {
                foreach ($content['category'] as $cat) {
                    $category = $em->getRepository(Category::class)->find($cat);
                    $product->addCategory($category);
                }
            }
            $errors = $validator->validate($product);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $em->persist($product);
            $em->flush();
            return $this->json($product, 201, [], ['groups' => ['product:read', 'brand:list', 'category:list']]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->json($product, 200, [], ['groups' => ['product:read', 'brand:list', 'category:list']]);
    }

    /**
     * @Route("/{id}", name="app_product_edit", methods={"PUT"})
     */
    public function edit(Request $request, Product $product, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            $content = json_decode($request->getContent(), true);
            $product = $serializer->deserialize($request->getContent(), Product::class, 'json', ['object_to_populate' => $product]);
            if (isset($content['brand'])) {
                $brand = $em->getRepository(Brand::class)->find($content['brand']);
                $product->setBrand($brand);
            }
            //remplacer les categories
            if (isset($content['category'])) {
                $product->removeAllCategories();
                foreach ($content['category'] as $cat) {
                    $category = $em->getRepository(Category::class)->find($cat);
                    $product->addCategory($category);
                }
            }
            $errors = $validator->validate($product);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $em->persist($product);
            $em->flush();
            return $this->json($product, 200, [], ['groups' => ['product:read', 'brand:list', 'category:list']]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", name="app_product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $productRepository->remove($product);
        return $this->json(['message' => 'Produit supprimée'], 200);
    }
}
