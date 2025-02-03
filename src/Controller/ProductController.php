<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private const PRODUCTS_LIMIT = 5;

    #[Route('/products', name: 'products', methods: ['GET'])]
    public function getProducts(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $category = $request->query->get('category');
        $priceLessThan = $request->query->get('priceLessThan');
        $products = $productRepository->findFilteredProducts($category, $priceLessThan, self::PRODUCTS_LIMIT);
        $productsWithDiscounts = $this->applyDiscounts($products);
        return new JsonResponse($productsWithDiscounts);
    }

    private function applyDiscounts(array $products): array
    {
        $discountedProducts = [];
        foreach ($products as $product) {
            $discount = $this->calculateDiscount($product);
            $finalPrice = $discount ? $product->getPrice() * (1 - $discount / 100) : $product->getPrice();

            $discountedProducts[] = [
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'category' => $product->getCategory(),
                'price' => [
                    'original' => $product->getPrice(),
                    'final' => $finalPrice,
                    'discount_percentage' => $discount ? "{$discount}%" : null,
                    'currency' => 'EUR',
                ],
            ];
        }

        return $discountedProducts;
    }

    private function calculateDiscount(Product $product): ?int
    {
        $discount = null;

        if ($product->getCategory() === 'boots') {
            $discount = 30;
        }

        if ($product->getSku() === '000003') {
            $discount = max($discount, 15);
        }

        return $discount;
    }
}