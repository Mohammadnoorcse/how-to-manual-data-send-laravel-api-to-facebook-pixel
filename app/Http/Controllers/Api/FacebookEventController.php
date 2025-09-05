<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FacebookApiService;
use Illuminate\Http\Request;

class FacebookEventController extends Controller
{
    protected $facebookApi;

    public function __construct(FacebookApiService $facebookApi)
    {
        $this->facebookApi = $facebookApi;
    }

    public function trackViewContent(Request $request)
    {
        // Simulate a product viewed
        $product = ['id' => 'product-101', 'name' => 'Fancy Widget', 'price' => 19.99];
        $userData = ['em' => hash('sha256', 'visitor@example.com')];
        $customData = [
            'content_ids' => [$product['id']],
            'content_type' => 'product',
            'content_name' => $product['name'],
            'value' => $product['price'],
            'currency' => 'USD',
        ];

        $this->facebookApi->sendEvent('ViewContent', $userData, $customData);

        return response()->json(['message' => 'ViewContent event sent.']);
    }

    public function trackAddToCart(Request $request)
    {
        // Simulate adding a product to the cart
        $product = ['id' => 'product-101', 'name' => 'Fancy Widget', 'price' => 19.99];
        $userData = ['em' => hash('sha256', 'customer@example.com')];
        $customData = [
            'content_ids' => [$product['id']],
            'content_type' => 'product',
            'value' => $product['price'],
            'currency' => 'USD',
        ];

        $this->facebookApi->sendEvent('AddToCart', $userData, $customData);

        return response()->json(['message' => 'AddToCart event sent.']);
    }

    public function trackInitiateCheckout(Request $request)
    {
        // Simulate a customer starting the checkout process
        $cartValue = 50.00;
        $cartItems = ['product-101', 'product-102'];
        $userData = ['em' => hash('sha256', 'customer@example.com')];
        $customData = [
            'value' => $cartValue,
            'currency' => 'USD',
            'content_ids' => $cartItems,
            'content_type' => 'product',
            'num_items' => count($cartItems),
        ];

        $this->facebookApi->sendEvent('InitiateCheckout', $userData, $customData);

        return response()->json(['message' => 'InitiateCheckout event sent.']);
    }

    public function trackPurchase(Request $request)
    {
        // ... same as the previous example ...
        $order = ['id' => 123, 'total' => 99.99, 'currency' => 'USD'];
        $userData = ['em' => hash('sha256', 'customer@example.com')];
        $customData = [
            'value' => $order['total'],
            'currency' => $order['currency'],
            'content_ids' => ['product-101', 'product-102'],
            'content_type' => 'product',
        ];

        $this->facebookApi->sendEvent('Purchase', $userData, $customData);

        return response()->json(['message' => 'Purchase event sent.']);
    }
}