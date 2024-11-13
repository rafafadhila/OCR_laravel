<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcrResult extends Model
{
    use HasFactory;
    protected $fillable = ['file_name','file_path', 'extracted_text', 'page_number'];
}
