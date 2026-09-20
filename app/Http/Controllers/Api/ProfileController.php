<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function uploadKk(Request $request)
    {
        $request->validate([
            'kk_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('kk_file')) {
            // Delete old KK if exists
            if ($user->kk_path && Storage::disk('public')->exists($user->kk_path)) {
                Storage::disk('public')->delete($user->kk_path);
            }

            $path = $request->file('kk_file')->store('kk_documents', 'public');
            $user->kk_path = $path;
            $user->is_kk_approved = false; // Reset approval status upon new upload
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Kartu Keluarga berhasil diunggah. Menunggu verifikasi admin.',
                'kk_path' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Gagal mengunggah KK'], 400);
    }

    public function getFamilyMembers(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'data' => $user->familyMembers
        ]);
    }

    public function addFamilyMember(Request $request)
    {
        $user = $request->user();

        // Ensure user has KK uploaded
        if (!$user->kk_path) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus mengunggah Kartu Keluarga (KK) terlebih dahulu.'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'relationship' => 'required|string|max:255',
        ], [
            'nik.size' => 'NIK harus persis 16 karakter angka.',
            'nik.required' => 'Kolom NIK wajib diisi.',
            'name.required' => 'Kolom Nama Lengkap wajib diisi.',
            'relationship.required' => 'Kolom Hubungan Keluarga wajib diisi.',
        ]);

        $familyMember = $user->familyMembers()->create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Anggota keluarga berhasil ditambahkan.',
            'data' => $familyMember
        ]);
    }

    public function deleteFamilyMember(Request $request, $id)
    {
        $user = $request->user();
        $familyMember = $user->familyMembers()->find($id);

        if (!$familyMember) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
        }

        $familyMember->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Anggota keluarga berhasil dihapus.'
        ]);
    }
}
