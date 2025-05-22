<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable; 

class Document extends Model
{
 
    use HasFactory, Searchable; 
    protected $fillable = [
        'title',
        'content',
        'file_path',
        'size',
        'category'
    ];


    

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}


