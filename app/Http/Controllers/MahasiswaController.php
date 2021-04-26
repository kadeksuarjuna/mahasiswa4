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
        //fungsi eloquent menampilkan data menggunakan pagination
        //$mahasiswas = Mahasiswa::all();
        //$mahasiswas = Mahasiswa::orderBy('Nim', 'desc')->paginate(6);
        //return view('mahasiswas.index', compact('mahasiswas'));
        //with('i', (request()->input('page', 1) - 1) * 6);
        $mahasiswas = Mahasiswa::with('kelas')->get();
        $paginate = Mahasiswa::orderby('Nim', 'asc')->paginate(3);
        return view('mahasiswas.index', ['mahasiswas'=>$mahasiswas,'paginate'=>$paginate]);
     }
    public function create()
    {
        $kelas = Kelas::all();
        return view('mahasiswas.create', ['kelas'=>$kelas]);
       // return view('mahasiswas.create');
    }
    public function store(Request $request)
    {

    //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
        ]);
        $mahasiswas = new Mahasiswa;
        $mahasiswas->Nim=$request->get('Nim');
        $mahasiswas->Nama=$request->get('Nama');
        $mahasiswas->Jurusan=$request->get('Jurusan');
        $mahasiswas->No_Handphone=$request->get('No_Handphone');
        $mahasiswas->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas');

        $mahasiswas->kelas()->associate($kelas);
        $mahasiswas->save();

        //fungsi eloquent untuk menambah data
        //Mahasiswa::create($request->all());

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswas.index')
        ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::find($Nim);
        return view('mahasiswas.detail', compact('Mahasiswa'));
    }

    public function edit($Nim)
    {

 //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = Mahasiswa::find($Nim);
        return view('mahasiswas.edit', compact('Mahasiswa'));
    }

    public function update(Request $request, $Nim)
    {

 //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
        ]);

 //fungsi eloquent untuk mengupdate data inputan kita
        Mahasiswa::find($Nim)->update($request->all());

//jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }
    public function destroy( $Nim)
     {
 //fungsi eloquent untuk menghapus data
         Mahasiswa::find($Nim)->delete();
        return redirect()->route('mahasiswas.index')
            -> with('success', 'Mahasiswa Berhasil Dihapus');
     }
};
