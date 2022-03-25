<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCategories extends Model
{

    protected $fillable = array('categoria');

    public $timestamps = false;

    protected $table = 'book_categories';
    protected $primaryKey = 'id';

}
