<?php

namespace Crater\Models;

use Salla\ZATCA\Tag;

class SallerName extends Tag
{
    public function __construct($value)
    {
        parent::__construct(1, $value);
    }
}
