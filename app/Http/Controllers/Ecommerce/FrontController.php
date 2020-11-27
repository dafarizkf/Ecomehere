<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Product;

class FrontController extends Controller
{
    public function index()
    {
    	$products = Product::orderBy('created_at','DESC')->paginate(10);
    	return view('ecommerce.index', compact('products'));
    }

    public function product()
	{
	    //BUAT QUERY UNTUK MENGAMBIL DATA PRODUK, LOAD PER PAGENYA KITA GUNAKAN 12 AGAR PRESISI PADA HALAMAN TERSEBUT KARENA DALAM SEBARIS MEMUAT 4 BUAH PRODUK
	    $products = Product::orderBy('created_at', 'DESC')->paginate(12);
	    //LOAD JUGA DATA KATEGORI YANG AKAN DITAMPILKAN PADA SIDEBAR
	    $categories = Category::with(['child'])->withCount(['child'])->getParent()->orderBy('name', 'ASC')->get();
	    //LOAD VIEW PRODUCT.BLADE.PHP DAN PASSING KEDUA DATA DIATAS
	    return view('ecommerce.product', compact('products', 'categories'));
	}
}
