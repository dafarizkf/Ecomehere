<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
	protected $guarded = [];
    //INI ADALAH ACCESSOR, JADI KITA MEMBUAT KOLOM BARU BERNAMA STATUS_LABEL
	//KOLOM TERSEBUT DIHASILKAN OLEH ACCESSOR, MESKIPUN FIELD TERSEBUT TIDAK ADA DITABLE PRODUCTS
	//AKAN TETAPI AKAN DISERTAKAN PADA HASIL QUERY
	public function getStatusLabelAttribute()
	{
	    //ADAPUN VALUENYA AKAN MENCETAK HTML BERDASARKAN VALUE DARI FIELD STATUS
	    if ($this->status == 0) {
	        return '<span class="badge badge-secondary">Draft</span>';
	    }
	    return '<span class="badge badge-success">Aktif</span>';
	}

	//FUNGSI YANG MENG-HANDLE RELASI KE TABLE CATEGORY
	public function category()
	{
	    return $this->belongsTo(Category::class);
	}

	public function setSlugAttribute($value)
	{
	    $this->attributes['slug'] = Str::slug($value); 
	}
}
