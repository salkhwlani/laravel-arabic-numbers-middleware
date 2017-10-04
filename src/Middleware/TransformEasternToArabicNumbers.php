<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Middleware;

class TransformEasternToArabicNumbers extends BaseNumbersMiddleware
{
    protected $from = 'eastern';

    /**
     * {@inheritdoc}
     */
    public function getExcept()
    {
        return parent::getExcept() + $this->getOption('except_from_eastern_to_arabic', []);
    }
}
