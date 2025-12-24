<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerLanguage extends Model
{
    use HasFactory;

    protected $table = 'freelancer_language';

    protected $fillable = [
        'language_name',
        'language_level',
        'language_file',
    ];

    public $timestamps = false;

    protected static function newFactory()
    {
        return \Modules\Freelancer\Database\factories\FreelancerLanguageFactory::new();
    }
}
