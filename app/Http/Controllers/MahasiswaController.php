<?php

namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\Kelas;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('kelas')->get();
        $paginate = Mahasiswa::orderBy('Nim', 'asc')->paginate(3);
        return view('mahasiswas.index', ['mahasiswas' => $mahasiswas, 'paginate'=>$paginate]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all(); //mendapatkan data dari tabel kelas
        return view('mahasiswas.create', ['kelas' => $kelas]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'email'=>'required',
            'tanggalLahir'=>'required',
        ]);

        $mahasiswas = new Mahasiswa;
        $mahasiswas->nim = $request->get('Nim');
        $mahasiswas->nama = $request->get('Nama');
        $mahasiswas->jurusan= $request->get('Jurusan');
        $mahasiswas->no_handphone= $request->get('No_Handphone');
        $mahasiswas->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas');

        //fungsi eloquent untuk menambah data dengan relasi belongto
        $mahasiswas->kelas()->associate($kelas);
        $mahasiswas->save();

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswas.index')
        ->with('success', 'Mahasiswa Berhasil Ditambahkan');
   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        //Code sebbelum dibuat relasi-->$mahasiswas = Mahasiswa::find($id);
        $mahasiswas = Mahasiswa::with('Kelas')->where('Nim', $id)->first();
        return view('mahasiswas.detail', ['mahasiswas' => $mahasiswas]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $mahasiswas = Mahasiswa::with('kelas')->where('Nim', $id)->first();
        $kelas = Kelas::all(); //mendapatkan data dari table kelas)
        return view('mahasiswas.edit', compact('mahasiswas', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //melakukan validasi data
        $request->validate([
        'Nim' => 'required',
        'Nama' => 'required',
        'Kelas' => 'required',
        'Jurusan' => 'required',
        'No_Handphone' => 'required',
        ]);

        $kelas = Kelas::find($request->get('kelas'));
        $mahasiswa = Mahasiswa::find($id);
        $mahasiswas = Mahasiswa::with('kelas')->where('Nim', $id)->first();
        $mahasiswas->nim = $request->get('Nim');
        $mahasiswas->nama = $request->get('Nama');
        $mahasiswas->jurusan= $request->get('Jurusan');
        $mahasiswas->no_handphone= $request->get('No_Handphone');
        $mahasiswas->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas');
        //fungsi eloquent untuk menambah data dengan relasi belongto
        $mahasiswas->kelas()->associate($kelas);
        $mahasiswas->save();

        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswas.index')->with('success', 'Mahasiswa Berhasil Diupdate');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //fungsi eloquent untuk menghapus data
        Mahasiswa::find($id)->delete();
        return redirect()->route('mahasiswas.index')
        -> with('success', 'Mahasiswa Berhasil Dihapus');

    }
    public function search(Request $request)
    {
        $mahasiswas = Mahasiswa::where([
            ['Nama', '!=', null, 'OR', 'NIM', '!=', null, 'OR', 'Kelas', '!=', null, 'OR', 'Jurusan', '!=', null],
            [function ($query) use ($request){
                if (($keyword = $request->keyword)) {
                    $query  ->orWhere('Nama', 'like', "%{$keyword}%")
                            ->orWhere('NIM', 'like', "%{$keyword}%")
                            ->orWhere('Kelas', 'like', "%{$keyword}%")
                            ->orWhere('Jurusan', 'like', "%{$keyword}%");
                }
            }]
        ])
        ->orderBy('NIM')
        ->paginate(5);
    
        return view('mahasiswas.index', compact('mahasiswas'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
};
