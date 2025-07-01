<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $pegawais = Pegawai::with('jabatan')
            ->when($keyword, function ($query, $keyword) {
                $query->where('username', 'like', "%{$keyword}%")
                      ->orWhere('nama_lengkap', 'like', "%{$keyword}%");
            })
            ->get();

        return view('admin.pegawai.index', compact('pegawais', 'keyword'));
    }

}
