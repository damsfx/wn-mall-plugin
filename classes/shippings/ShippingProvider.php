<?php

namespace Winter\Mall\Classes\Shippings;

use Illuminate\Support\Facades\Session;
use Winter\Mall\Models\Order;
use Winter\Mall\Models\ShippingProvidersSettings;
use Winter\Storm\Exception\ValidationException;

/**
 * A ShippingProvider handles the integration with external shipping providers.
 */
abstract class ShippingProvider
{
    /**
     * The order that is being paid.
     *
     * @var Order
     */
    public $order;

    /**
     * Data that is needed for the payment.
     *
     * @var array
     */
    public $data;


    /**
     * PaymentProvider constructor.
     *
     * Optionally pass an order or payment data.
     *
     * @param Order|null $order
     * @param array      $data
     */
    public function __construct(?Order $order = null, array $data = [])
    {
        if ($order) {
            $this->setOrder($order);
        }
        if ($data) {
            $this->setData($data);
        }
    }


    /**
     * Return the display name of this shipping provider.
     *
     * @return string
     */
    abstract public function name(): string;

    /**
     * Return a unique identifier for this shipping provider.
     *
     * @return string
     */
    abstract public function identifier(): string;

    /**
     * Return any custom backend settings fields.
     *
     * @return array
     */
    abstract public function settings(): array;

    /**
     * Validate the given input data for this shipping.
     *
     * @throws ValidationException
     * @return bool
    */
    abstract public function validate(): bool;

    /**
     * Fields returned from this shipping provider are stored encrypted.
     *
     * Use this to store API tokens and other secret data
     * that is needed for this ShippingProvider to work.
     *
     * @return array
     */
    public function encryptedSettings(): array
    {
        return [];
    }
    

    /**
     * Set the order that is being paid.
     *
     * @param null|Order
     *
     * @return ShippingProvider
     */
    public function setOrder(?Order $order)
    {
        $this->order = $order;
        Session::put('mall.shipping.order', optional($this->order)->id);

        return $this;
    }

    /**
     * Set the data for this shipping.
     *
     * @param array $data
     *
     * @return ShippingProvider
     */
    public function setData(array $data)
    {
        $this->data = $data;
        Session::put('mall.shipping.data', $data);

        return $this;
    }

    /**
     * Get the settings of this PaymentProvider.
     *
     * @return \Winter\Storm\Support\Collection
     */
    public function getSettings()
    {
        return collect($this->settings())->mapWithKeys(fn ($settings, $key) => [$key => ShippingProvidersSettings::get($key)]);
    }
}