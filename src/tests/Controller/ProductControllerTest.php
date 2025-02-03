<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testGetProducts(): void
    {
        $client = static::createClient();

        $client->request('GET', '/products?category=boots&priceLessThan=90000');

        $this->assertResponseIsSuccessful();

        $responseContent = $client->getResponse()->getContent();

        $this->assertJson($responseContent);

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertLessThanOrEqual(5, count($responseData));

        foreach ($responseData as $product) {
            $this->assertArrayHasKey('sku', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('category', $product);
            $this->assertArrayHasKey('price', $product);

            $this->assertArrayHasKey('original', $product['price']);
            $this->assertArrayHasKey('final', $product['price']);
            $this->assertArrayHasKey('discount_percentage', $product['price']);
            $this->assertArrayHasKey('currency', $product['price']);

            $this->assertEquals('EUR', $product['price']['currency']);

            $this->assertLessThanOrEqual($product['price']['original'], $product['price']['final']);

            if ($product['category'] === 'boots') {
                $this->assertEquals('30%', $product['price']['discount_percentage']);
                $this->assertEquals($product['price']['original'] * 0.7, $product['price']['final']);
            } elseif ($product['sku'] === '000003') {
                $this->assertEquals('15%', $product['price']['discount_percentage']);
                $this->assertEquals($product['price']['original'] * 0.85, $product['price']['final']);
            } else {
                $this->assertNull($product['price']['discount_percentage']);
                $this->assertEquals($product['price']['original'], $product['price']['final']);
            }
        }
    }


    public function testGetProductsWithoutFilters(): void
    {
        $client = static::createClient();
        $client->request('GET', '/products');
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertLessThanOrEqual(5, count($responseData));
    }


    public function testGetProductsWithPriceLessThanFilter(): void
    {
        $client = static::createClient();

        $client->request('GET', '/products?priceLessThan=70000');

        $this->assertResponseIsSuccessful();

        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);


        foreach ($responseData as $product) {
            $this->assertLessThanOrEqual(70000, $product['price']['original']);
        }
    }


    public function testGetProductsWithCategoryFilter(): void
    {
        $client = static::createClient();

        $client->request('GET', '/products?category=sneakers');

        $this->assertResponseIsSuccessful();

        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        foreach ($responseData as $product) {
            $this->assertEquals('sneakers', $product['category']);
        }
    }
}