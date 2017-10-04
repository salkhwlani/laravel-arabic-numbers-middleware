<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Middleware;

class TransformArabicToEasternNumbers extends BaseNumbersMiddleware
{
    protected $from = 'arabic';

    /**
     * {@inheritdoc}
     */
    public function getExcept()
    {
        return parent::getExcept() + $this->getOption('except_from_arabic_to_eastern', []);
    }
}
