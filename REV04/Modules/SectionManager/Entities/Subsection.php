<?php

namespace Modules\SectionManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Entities\Users;

class Subsection extends Model
{
    use HasFactory;

    protected $table = 'subsection';

    protected $fillable = [
        'section_id',
        'title',
    ];

    public $timestamps = false;

    public function Users() {
        return $this->belongsToMany(Users::class)->select('id', 'first_name', 'last_name');
    }

    public function Appraiser() {
        return $this->belongsToMany(Users::class, 'subsection_appraiser')->select('id', 'first_name', 'last_name', 'phone');
    }

    public function Section() {
        return $this->belongsTo(Section::class);
    }

//    protected static function newFactory()
//    {
//        return \Modules\SectionManager\Database\factories\SubSectionManagerFactory::new();
//    }
}
