<?php

namespace Mdafzaran\Idpay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdIdPayInfo extends Model
{
    use HasFactory;
    protected $table = 'mdidpayinfo';
    protected $guarded = [];
}
