<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Artikel;

class ArtikelController extends Controller
{
    protected $artikel = []; // Simpan artikel dalam memori untuk demonstrasi

    public function index(Request $request)
    {
        // Get perPage parameter, default to 9
        $perPage = $request->input('perPage', 9);
        
        // Ambil semua artikel dari database, urutkan berdasarkan tanggal terbaru
        $artikels = Artikel::orderBy('tanggal', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate($perPage);

        return view('artikel', compact('artikels'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar_artikel' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'isi_artikel' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        // Simpan gambar ke dalam storage/artikel
        $imagePath = null;
        if ($request->hasFile('gambar_artikel')) {
            $imagePath = $request->file('gambar_artikel')->store('artikel', 'public');
            // Ambil hanya nama file, bukan path lengkap
            $imagePath = basename($imagePath);
        }

        // Buat artikel baru dan simpan ke database
        Artikel::create([
            'judul' => $request->judul,
            'gambar_artikel' => $imagePath, // Simpan hanya nama file
            'isi_artikel' => $request->isi_artikel,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil ditambahkan!');
    }

    /**
     * Menghapus artikel dari database
     */
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        
        // Hapus file gambar jika ada
        if ($artikel->gambar_artikel) {
            $imagePath = 'public/artikel/' . $artikel->gambar_artikel;
            if (\Storage::exists($imagePath)) {
                \Storage::delete($imagePath);
            }
        }
        
        // Hapus artikel dari database
        $artikel->delete();
        
        return redirect()->route('artikel.index')->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Menampilkan detail artikel
     */
    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('artikel-detail', compact('artikel'));
    }

    /**
     * Mencari artikel berdasarkan judul
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $perPage = $request->input('perPage', 9);
        
        // Jika keyword kosong, kembalikan semua artikel
        if (empty($keyword)) {
            return redirect()->route('artikel.index');
        }
        
        // Cari artikel berdasarkan judul atau isi artikel
        $artikels = Artikel::where('judul', 'like', '%' . $keyword . '%')
                          ->orWhere('isi_artikel', 'like', '%' . $keyword . '%')
                          ->orderBy('tanggal', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate($perPage);
        
        return view('artikel', compact('artikels', 'keyword'));
    }
}
