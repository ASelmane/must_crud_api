<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/brand")
 */
class BrandController extends AbstractController
{
    /**
     * @Route("", name="app_brand_list", methods={"GET"})
     */
    public function list(BrandRepository $brandRepository): Response
    {
        return $this->json($brandRepository->findAll(), 200, [], ['groups' => 'brand:list']);
    }

    /**
     * @Route("", name="app_brand_new", methods={"POST"})
     * api post new brand json
     */
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            $brand = $serializer->deserialize($request->getContent(), Brand::class, 'json');
            $errors = $validator->validate($brand);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $em->persist($brand);
            $em->flush();
            return $this->json($brand, 201, [], ['groups' => ['brand:list', 'brand:read']]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", name="app_brand_show", methods={"GET"})
     * api get brand products json
     */
    public function show(Brand $brand): Response
    {
        return $this->json($brand, 200, [], ['groups' => ['brand:list', 'brand:read','category:list']]);
    }

    /**
     * @Route("/{id}", name="app_brand_edit", methods={"PUT"})
     * api put brand json
     */
    public function edit(Request $request, Brand $brand, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        try {
            $serializer->deserialize($request->getContent(), Brand::class, 'json', ['object_to_populate' => $brand]);
            $errors = $validator->validate($brand);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }
            $em->persist($brand);
            $em->flush();
            return $this->json($brand, 200, [], ['groups' => ['brand:list', 'brand:read','category:list']]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
    
    /**
     * @Route("/{id}", name="app_brand_delete", methods={"DELETE"})
     * api delete brand json
     */
    public function delete(Brand $brand, BrandRepository $brandRepository): Response
    {
        $brandRepository->remove($brand);
        return $this->json(['message' => 'Marque supprimée'], 200);
    }
}
