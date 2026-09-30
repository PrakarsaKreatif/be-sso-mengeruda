<!DOCTYPE html>
<html>
<head>
    <title>Akun Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
        <h2 style="color: #059669;">Selamat! Akun Anda Telah Disetujui</h2>
        <p>Halo <strong>{{ $user->name }}</strong>,</p>
        <p>Kabar baik! Akun E-Surat Desa Mengeruda Anda telah diverifikasi dan disetujui oleh Admin.</p>
        
        <p>Sekarang Anda sudah dapat menggunakan semua layanan di sistem E-Surat kami, seperti:</p>
        <ul style="color: #475569;">
            <li>Mengajukan berbagai permohonan surat resmi.</li>
            <li>Memantau status permohonan surat secara real-time.</li>
            <li>Mendownload surat yang telah disetujui dalam format PDF.</li>
        </ul>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ env('FRONTEND_URL', 'http://localhost:5173') }}/login" style="background-color: #1e3a8a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Masuk ke Dashboard</a>
        </div>
        
        <p style="margin-top: 30px; font-size: 14px; color: #64748b;">
            Terima kasih,<br>
            Pemerintah Desa Mengeruda
        </p>
    </div>
</body>
</html>
