<?php

namespace Yemenifree\LaravelArabicNumbersMiddleware\Middleware;

class TransformEasternToArabicNumbers extends BaseNumbersMiddleware
{
    /**
     * {@inheritdoc}
     */
    public function getExcept(): array
    {
        return array_merge(parent::getExcept(), $this->getOption('except_from_eastern_to_arabic', []));
    }
}
