<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;

    public $table = 'drugs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'rxcui',
        'name',
        'synonym',
        'language',
        'psn',
        'created_at',
        'updated_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'drug_user')->withTimestamps();
    }
}
