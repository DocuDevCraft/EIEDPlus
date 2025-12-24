<?php

namespace Modules\FileLibrary\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileLibrary extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany('Modules\Users\Entities\Users', 'post_users', 'id', 'avatar');
    }

    protected $table = 'file_library';
    protected $fillable = [
        'org_name',
        'file_name',
        'path',
        'extension',
        'file_type',
        'type',
        'used'
    ];
    public $timestamps = true;

    protected static function newFactory()
    {
        return \Modules\FileLibrary\Database\factories\FileLibraryFactory::new();
    }
}
