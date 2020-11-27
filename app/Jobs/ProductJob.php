<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\ProductImport; //IMPORT CLASS PRODUCTIMPORT YANG AKAN MENG-HANDLE FILE EXCEL
use Illuminate\Support\Str;
use App\Product;
use File;

class ProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $category;
    protected $filename;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($category, $filename)
    {
        $this->category = $category;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //menyimpan data excel yang diupload dan merubah file excel menjadi array (convert)
        $files = (new ProductImport)->toArray(storage_path('app/public/uploads/' .$this->filename));

        //looping data 
        foreach ($files[0] as $row){

            //menambahkan format url dengan mengambil nama file dan extensionnya
            //diakhiri dengan nama file yang lengkap dengan extension 
            $explodeURL = explode('/', $row[4]);
            $explodeExtension = explode('.', end($explodeURL));
            $filename = time() . Str::random(6) . '.' . end($explodeExtension);

            //mendownload gambar dari url 
            file_put_contents(storage_path('app/public/products') . '/' . $filename, file_get_contents($row[4]));   

            //kemudian disimpan ke database
            Product::create([
                'name' => $row[0],
                'slug' => $row[0],
                'category_id' => $this->category,
                'description' => $row[1],
                'price' => $row[2],
                'weight' => $row[3],
                'image' => $filename,
                'status' => true
                ]);
            }
             //file akan otomatis terhapus jika sudah selesai
    File::delete(storage_path('app/public/uploads/' . $this->filename));
    }
   
}
