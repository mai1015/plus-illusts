<?php

namespace Mai1015\PlusIllusts\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\User;

class PixivUser extends Model
{
    protected $guarded = ['id', 'status'];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
