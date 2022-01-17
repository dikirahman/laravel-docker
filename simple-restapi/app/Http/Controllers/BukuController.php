<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Facade\FlareClient\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::orderBy('created_at', 'DESC')->paginate(5);
        $response = [
            'message' => 'Data Buku',
            'data' => $buku,
        ];
        
        return response()->json(HttpFoundationResponse::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "kode_buku" => ['required'],
            "judul" => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            $buku = Buku::create($request->all());

            $response = [
                'message' => 'Berhasil disimpan',
                'data' => $buku,
            ];

            return response()->json($response, HttpFoundationResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Gagal " . $e->errorInfo,
            ]);
        }
    }

    public function show($kode_buku)
    {
        $buku = Buku::where('kode_buku', $kode_buku)->firstOrFail();
        if (is_null($buku)) {
            return $this->sendError('Buku tidak diemukan');
        }
        return response()->json([
            "success" => true,
            "message" => "Data Buku ditemukan.",
            "data" => $buku,
        ]);
    }

    public function update(Request $request, $kode_buku)
    {
        $buku = BUku::find($kode_buku);
        $buku->update($request->all());
        return response()->json([
            "success" => true,
            "message" => "Data Buku telah diubah.",
            "data" => $buku,
        ]);
    }

    public function destroy($kode_buku)
    {
        $deletedRows = Buku::where('kode_buku', $kode_buku)->delete();
        return response()->json([
            "success" => true,
            "message" => "Data Buku berhasil dihapus.",
            "data" => $deletedRows,
        ]);
    }
}