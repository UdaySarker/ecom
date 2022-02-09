<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class OldBookSaleModel extends Model
{
    protected $fillable=['user_id','slug','photo','title','summary','description','product_img','pages','stock','cat_id','publisher_id','author_id','child_cat_id','status','condition','price','discount'];
    protected $table="old_books";
    public static function getOldBookByUser($user_id)
    {
        return OldBookSaleModel::all()->where('user_id','=',$user_id);
    }
}
