<?php

namespace Mai1015\PlusIllusts\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\Tag;

class Illust extends Model
{
    protected $guarded = ['id'];

    public function files() {
        return $this->hasMany(IllustFile::class, 'illust_id');
    }

    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable', 'pixiv_taggables')
            ->withTimestamps();
    }

    public function author() {
        return $this->belongsTo(PixivUser::class, 'pixiv_id');
    }
}
