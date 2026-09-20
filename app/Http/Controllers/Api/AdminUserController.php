<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function getPendingUsers(Request $request)
    {
        // Pastikan hanya admin yang bisa mengakses
        $user = auth()->guard('api')->user();
        if (!$user || !$user->roles()->whereIn('name', ['Super Admin', 'admin_surat'])->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::with('familyMembers')->where('is_approved', false)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'warga');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function approveUser(Request $request, $id)
    {
        $admin = auth()->guard('api')->user();
        if (!$admin || !$admin->roles()->whereIn('name', ['Super Admin', 'admin_surat'])->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User approved successfully'
        ]);
    }

    public function getAllUsers(Request $request)
    {
        $admin = auth()->guard('api')->user();
        if (!$admin || !$admin->roles()->whereIn('name', ['Super Admin', 'admin_surat'])->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::with('familyMembers')->whereHas('roles', function ($query) {
                $query->where('name', 'warga');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function approveKk(Request $request, $id)
    {
        $admin = auth()->guard('api')->user();
        if (!$admin || !$admin->roles()->whereIn('name', ['Super Admin', 'admin_surat'])->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->is_kk_approved = true;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen KK disetujui.'
        ]);
    }

    public function rejectKk(Request $request, $id)
    {
        $admin = auth()->guard('api')->user();
        if (!$admin || !$admin->roles()->whereIn('name', ['Super Admin', 'admin_surat'])->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        
        // Hapus file KK jika ditolak
        if ($user->kk_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->kk_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->kk_path);
        }
        
        $user->kk_path = null;
        $user->is_kk_approved = false;
        $user->save();
        
        // Hapus semua data keluarga yang berstatus pending ini
        $user->familyMembers()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Dokumen KK ditolak dan dihapus beserta data keluarga terkait.'
        ]);
    }
}
