<?php

namespace Mai1015\PlusIllusts\Models;

use Illuminate\Database\Eloquent\Model;

class IllustFile extends Model
{
    public function illust()
    {
        return $this->belongsTo(Illust::class, 'illust_id');
    }
    /**
     * Scope a query with hash
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $hash
     * @return \Illuminate\Database\Eloquent\Builder
     * @author mai1015 <i@mai1015.com>
     */
    public function scopeHash($query, $hash)
    {
        return $query->where('hash', $hash);
    }
}
