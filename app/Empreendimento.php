<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Empreendimento extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $table = 'empreendimentos';
    public $incrementing = false;
  
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::orderedUuid());
        });
    }

    protected $fillable = [
        'name',
    ];

    public function leads()
    {
        return $this->hasMany('App\Lead');
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = Str::title($value);
    }
}