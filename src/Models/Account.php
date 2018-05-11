<?php

namespace App\Models;

/**
 * 用户模型
 *
 * Class Account
 * @package App\Models
 */
class Account extends Model
{
    protected $table = 'account';

    protected $guarded = [];

    public function tags()
    {
        return $this->hasMany(Tag::class, 'account_id', 'id');
    }

}