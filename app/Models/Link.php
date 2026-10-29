<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['name', 'type', 'url', 'parent_id'];

    public function children()
    {
        return $this->hasMany(Link::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Link::class, 'parent_id');
    }
}
