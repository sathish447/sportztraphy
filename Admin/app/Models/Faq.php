<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Faq extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'faq';
}