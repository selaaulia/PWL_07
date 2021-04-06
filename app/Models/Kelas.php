<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Model\Mahasiswa;

class Kelas extends Model
{
    use HasFactory;
    protected $table='kelas'; //Mendefinikasn bahwa model ini terkait dengan tabel kelas

    public function mahasiswas(){
        return $this->hasMany(Mahasiswa::class);
    }
}
