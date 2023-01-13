<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string title
 * @property string description
 * @property string image
 * @property float rate
 * @property int category_id
 */
class Movie extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    protected $appends=['full_path_image'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function getFullPathImageAttribute(){
        return $this->image != null ? asset('storage/'.$this->image) : null;
    }
    public function setRateAttribute($rate){
        $this->attributes['rated_by'] ++ ;
        $this->attributes['rate'] = ($this->attributes['rate']+$rate)/$this->attributes['rated_by'];
    }
}
