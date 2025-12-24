<?php

namespace Modules\SectionManager\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class Division extends Model
{
    use HasFactory;

    protected $table = 'division';

    protected $fillable = [
        'subsection_id',
        'title',
    ];

    public $timestamps = false;

    public function Users()
    {
        return $this->belongsTo(Users::class)->select('id', 'first_name', 'last_name');
    }

    public function Appraiser()
    {
        return $this->belongsToMany(Users::class, 'division_appraiser')->select('id', 'first_name', 'last_name', 'phone');
    }

    public function Subsection()
    {
        return $this->belongsTo(Subsection::class);
    }

    protected static function newFactory()
    {
        return \Modules\SectionManager\Database\factories\DivisionManagerFactory::new();
    }
}
