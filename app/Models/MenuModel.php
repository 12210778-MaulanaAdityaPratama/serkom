<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    protected $table = 'makanan';
    protected $fillable = ['nama_makanan', 'deskripsi', 'harga', 'kategori', 'gambar'];
    use HasFactory;
}
