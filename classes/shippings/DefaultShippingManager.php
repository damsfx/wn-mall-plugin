<?php

namespace Winter\Mall\Classes\Shippings;

use InvalidArgumentException;
use Winter\Mall\Classes\Shippings\ShippingProvider;
use Winter\Mall\Classes\Shippings\ShippingsManager;
use Winter\Mall\Models\ShippingMethod;
// use LogicException;
// use Winter\Mall\Models\Order;
// use Winter\Mall\Models\ShippingMethod;
// use Session;

/**
 * The DefaultShippingsManager is responsible for the orchestration
 * of all available shipping providers.
 *
 * When a shipping is being processed, the gateway sets up
 * all needed data to process this shipping.
 */
class DefaultShippingManager implements ShippingsManager
{
    /**
     * The currently active ShippingProvider.
     * @var ShippingProvider
     */
    protected $provider;

    /**
     * An array of all registered ShippingProviders.
     * @var ShippingProvider[]
     */
    protected $providers = [];

    /**
     * {@inheritdoc}
     */
    public function registerProvider(ShippingProvider $provider): ShippingProvider
    {
        $this->providers[$provider->identifier()] = $provider;

        return $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderById(string $identifier): ShippingProvider
    {
        if (! isset($this->providers[$identifier])) {
            throw new InvalidArgumentException(sprintf('Shipping provider %s is not registered.', $identifier));
        }

        return $this->providers[$identifier];
    }

    /**
     * {@inheritdoc}
    */
    public function init(ShippingMethod $shippingMethod, array $data)
    {
        $this->provider = $this->getProviderForMethod($shippingMethod);
        $this->provider->setData($data);
        $this->provider->validate();
    }

    /**
     * {@inheritdoc}
    public function process(Order $order): ShippingResult
    {
        if (! $this->provider) {
            throw new LogicException('Missing data for shipping. Make sure to call init() before process()');
        }
        
        Session::put('mall.shipping.id', str_random(8));

        $this->provider->setOrder($order);
        $result = new ShippingResult($this->provider, $order);
        
        return $this->provider->process($result);
    }
    */

    /**
     * {@inheritdoc}
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveProvider(): ShippingProvider
    {
        return $this->provider;
    }

    /**
     * Get the ShippingProvider that belongs to a ShippingMethod.
     *
     * @param ShippingMethod $method
     *
     * @return ShippingProvider
     */
    protected function getProviderForMethod(ShippingMethod $method): ShippingProvider
    {
        if (isset($this->providers[$method->shipping_provider])) {
            return new $this->providers[$method->shipping_provider];
        }

        throw new \LogicException(
            sprintf('The selected shipping provider "%s" is unavailable.', $method->shipping_provider)
        );
    }
}