<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generalization extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable=['title_ar','title_en', 'content_ar', 'content_en' ,'image'];

    protected $visible= ['id', 'title','content','image'];


    public static  function getData()
    {
        $locale = app()->getLocale();

        $contentColumn = $locale === 'ar' ? 'content_ar' : 'content_en';
        $titleColumn = $locale === 'ar' ? 'title_ar' : 'title_en';

        return self::selectRaw("id, $titleColumn as title, $contentColumn as content, image");

    }


    public static  function getDataById($id)
    {
        $locale = app()->getLocale();

        $contentColumn = $locale === 'ar' ? 'content_ar' : 'content_en';
        $titleColumn = $locale === 'ar' ? 'title_ar' : 'title_en';

        return self::selectRaw("id, $titleColumn as title, $contentColumn as content, image")
        ->where('id', $id)
        ->first(); // Retrieve the first matching record
    }

}
