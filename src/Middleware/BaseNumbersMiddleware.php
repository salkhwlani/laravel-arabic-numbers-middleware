<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Middleware;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;
use Illuminate\Support\Arr;

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
     * The additional attributes passed to the middleware.
     *
     * @var array
     */
    protected $attributes = [];

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
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  ...$attributes
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$attributes)
    {
        $this->attributes = $attributes;

        return parent::handle($request,$next);
    }

    /**
     * get except fields.
     *
     * @return array
     */
    public function getExcept(): array
    {
        return array_merge($this->except, $this->getOption('except_from_all', [])) + $this->attributes;
    }

    /**
     * get options from config.
     * @param string $key
     * @param null $default
     * @return array
     */
    protected function getOption($key, $default = null): array
    {
        return Arr::get($this->config, $key, $default);
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
    protected function transformNumber($value): string
    {
        return strtr($value, $this->getNumbers());
    }

    /**
     * get array numbers to transforms.
     *
     * @return array
     */
    protected function getNumbers(): array
    {
        return $this->isFromEastern() ? array_flip($this->getEasternNumbers()) : $this->getEasternNumbers();
    }

    /**
     * check if transform from (arabic|english) to eastern.
     *
     * @return bool
     */
    public function isFromEastern(): bool
    {
        return $this->from === 'eastern';
    }

    /**
     * Get eastern numbers array.
     *
     * @return array
     */
    public function getEasternNumbers(): array
    {
        return $this->easternNumbers;
    }
}
