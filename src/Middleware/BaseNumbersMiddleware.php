<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Middleware;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;

abstract class BaseNumbersMiddleware extends TransformsRequest
{
    /** @var array */
    protected $except = [];

    /** @var string */
    protected $from = 'eastern';

    /** @var array */
    protected $easternNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    /** @var array|mixed */
    protected $config;

    /**
     * BaseNumbersMiddleware constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('arabic-numbers-middleware');
    }

    /**
     * get except fields.
     *
     * @return array
     */
    public function getExcept()
    {
        return $this->except + $this->getOption('except_from_all', []) + $this->attributes;
    }

    /**
     * get options from config.
     * @param string $key
     * @param null $default
     * @return array
     */
    protected function getOption($key, $default = null)
    {
        return array_get($this->config, $key, $default);
    }

    /**
     * check if transform from (arabic|english) to eastern.
     *
     * @return bool
     */
    public function isFromArabic()
    {
        return $this->from == 'arabic';
    }

    /**
     * Transform the given value.
     *
     * @param  string $key
     * @param  mixed $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->getExcept(), true)) {
            return $value;
        }

        return is_string($value) ? $this->transformNumber($value) : $value;
    }

    /**
     * transform eastern/(arabic|english) numbers to (arabic|english)/eastern numbers inside string.
     *
     * @param string $value
     * @return string
     */
    protected function transformNumber($value)
    {
        return strtr($value, $this->getNumbers());
    }

    /**
     * get array numbers to transforms.
     *
     * @return string
     */
    protected function getNumbers()
    {
        return $this->isFromEastern() ? array_flip($this->getEasternNumbers()) : $this->getEasternNumbers();
    }

    /**
     * check if transform from (arabic|english) to eastern.
     *
     * @return bool
     */
    public function isFromEastern()
    {
        return $this->from == 'eastern';
    }

    /**
     * Get eastern numbers array.
     *
     * @return array
     */
    public function getEasternNumbers()
    {
        return $this->easternNumbers;
    }
}
