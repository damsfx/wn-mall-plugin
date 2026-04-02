<?php

namespace Winter\Mall\Classes\Shippings;

use Winter\Mall\Classes\Shippings\ShippingProvider;
use Winter\Mall\Models\ShippingMethod;
use Winter\Storm\Exception\ValidationException;

/**
 * The ShippingsManager is responsible for the orchestration
 * of all available shipping providers.
 */
interface ShippingsManager
{
    /**
     * Register a new ShippingProvider on this gateway.
     *
     * @param ShippingProvider $provider
     *
     * @return ShippingProvider
     */
    public function registerProvider(ShippingProvider $provider): ShippingProvider;

    /**
     * Initialize the ShippingGateway.
     *
     * @param ShippingMethod $shippingMethod
     * @param array         $data
     *
     * @throws ValidationException
     */
    public function init(ShippingMethod $shippingMethod, array $data);

    /**
     * Find a ShippingProvider by its ID.
     *
     * @param string $identifier
     *
     * @return ShippingProvider
     */
    public function getProviderById(string $identifier): ShippingProvider;

    /**
     * Get an array of all available providers.
     * @return array
     */
    public function getProviders(): array;

    /**
     * Get the currently active provider.
     *
     * @return ShippingProvider
     */
    public function getActiveProvider(): ShippingProvider;
}