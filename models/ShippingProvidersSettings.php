<?php

namespace Winter\Mall\Models;

use Illuminate\Support\Collection;
use Winter\Mall\Classes\Shippings\ShippingProvider;
use Winter\Mall\Classes\Shippings\ShippingsManager;
use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Encryptable;

class ShippingProvidersSettings extends Model
{
    use Encryptable;

    protected $encryptable = [];

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'winter_mall_shipping_providers_settings';

    public $settingsFields = '$/winter/mall/models/settings/fields_shipping_providers.yaml';

    /**
     * @var ShippingsManager
     */
    protected $shippingManager;

    /**
     * @var Collection<ShippingProvider>
     */
    protected $providers;

    public function __construct(array $attributes = [])
    {
        $this->shippingManager = app(ShippingsManager::class);
        $this->providers       = collect($this->shippingManager->getProviders());
        $this->providers->each(function ($provider) {
            $this->encryptable = array_merge($this->encryptable, $provider->encryptedSettings());
        });

        parent::__construct($attributes);
    }

    /**
     * Extend the setting form with input fields for each
     * registered plugin.
     */
    public function getFieldConfig()
    {
        if ($this->fieldConfig !== null) {
            return $this->fieldConfig;
        }

        $config                 = parent::getFieldConfig();
        $config->tabs['fields'] = [];

        $this->providers->each(function ($provider) use ($config) {
            $settings = $this->setDefaultTab($provider->settings(), $provider->name());

            $config->tabs['fields'] = array_merge($config->tabs['fields'], $settings);
        });

        return $config;
    }

    protected function setDefaultTab(array $settings, $tab)
    {
        return array_map(function ($i) use ($tab) {
            if ( ! isset($i['tab'])) {
                $i['tab'] = $tab;
            }

            return $i;
        }, $settings);
    }
}
