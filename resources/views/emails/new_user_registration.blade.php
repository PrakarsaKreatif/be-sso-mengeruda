<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Akun Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
        <h2 style="color: #1e3a8a;">Pendaftaran Akun Warga Baru</h2>
        <p>Halo Admin,</p>
        <p>Terdapat pendaftaran akun warga baru yang menunggu verifikasi Anda di sistem E-Surat Desa Mengeruda.</p>
        
        <div style="background-color: #f8fafc; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <p style="margin: 0 0 10px 0;"><strong>Nama Lengkap:</strong> {{ $user->name }}</p>
            <p style="margin: 0 0 10px 0;"><strong>NIK:</strong> {{ $user->nik }}</p>
            <p style="margin: 0 0 10px 0;"><strong>Email:</strong> {{ $user->email }}</p>
            <p style="margin: 0;"><strong>Nomor HP:</strong> {{ $user->phone }}</p>
        </div>

        <p>Silakan masuk ke halaman Admin (Verifikasi Akun) untuk melakukan pengecekan KTP dan menyetujui akun warga tersebut.</p>
        
        <p style="margin-top: 30px;">
            Terima kasih,<br>
            Sistem E-Surat Mengeruda
        </p>
    </div>
</body>
</html>
