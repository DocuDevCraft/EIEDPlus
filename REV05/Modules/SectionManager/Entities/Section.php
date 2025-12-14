<?php

namespace Modules\SectionManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Entities\Users;

class Section extends Model
{
    use HasFactory;

    protected $table = 'section';

    protected $fillable = [
        'title',
        'code',
    ];

    public $timestamps = false;

    public function Users() {
        return $this->belongsToMany(Users::class)->select('id', 'first_name', 'last_name');
    }

    public function ChiefAppraiser() {
        return $this->belongsToMany(Users::class, 'section_chief_appraiser')->select('id', 'first_name', 'last_name', 'phone');
    }

    public function SectionManager() {
        return $this->belongsToMany(Users::class, 'section_users')->select('id', 'first_name', 'last_name', 'phone');
    }

    public function SubSection() {
        return $this->belongsToMany(Subsection::class);
    }

    protected static function newFactory()
    {
        return \Modules\SectionManager\Database\factories\SectionManagerFactory::new();
    }
}
