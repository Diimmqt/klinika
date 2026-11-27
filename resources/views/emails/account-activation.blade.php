<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun KLINIKA</title>
</head>
<body style="margin:0;padding:0;background-color:#F4F8F8;font-family:Inter,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#182B35;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F4F8F8;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;background-color:#FFFFFF;border:1px solid #DDE8E8;border-radius:12px;overflow:hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#087F83;padding:24px 28px;">
                            <p style="margin:0;font-size:20px;font-weight:700;color:#FFFFFF;letter-spacing:-0.01em;">+ Klinika</p>
                            <p style="margin:4px 0 0;font-size:13px;color:#E1F0ED;">Undangan &amp; Aktivasi Akun Pengguna</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px;font-size:15px;font-weight:600;color:#182B35;">
                                Halo, {{ $user->name }}
                            </p>
                            <p style="margin:0 0 20px;font-size:14px;line-height:1.6;color:#475B63;">
                                Administrator telah mendaftarkan akun Anda pada sistem Rekam Medis Elektronik <strong>KLINIKA</strong>. Berikut rincian konfirmasi identitas dan modal awal login Anda:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:22px;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#627680;width:170px;vertical-align:top;">Nama Lengkap</td>
                                    <td style="padding:10px 0;border-bottom:1px solid #EEF3F3;font-size:13px;font-weight:600;color:#182B35;">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#627680;vertical-align:top;">Email Login</td>
                                    <td style="padding:10px 0;border-bottom:1px solid #EEF3F3;font-size:13px;font-weight:600;color:#087F83;">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#627680;vertical-align:top;">Role di Klinik / RS</td>
                                    <td style="padding:10px 0;border-bottom:1px solid #EEF3F3;font-size:13px;font-weight:600;color:#182B35;">{{ $roleLabel }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;font-size:13px;color:#627680;vertical-align:top;">Kata Sandi Awal</td>
                                    <td style="padding:10px 0;font-size:14px;font-weight:700;color:#182B35;">
                                        <code style="display:inline-block;background-color:#F0FDFA;border:1px solid #99F6E4;color:#0F766E;padding:4px 10px;border-radius:6px;font-family:Consolas,Monaco,monospace;letter-spacing:0.04em;">{{ $initialPassword }}</code>
                                    </td>
                                </tr>
                            </table>

                            <div style="background-color:#FAFCFC;border:1px solid #DDE8E8;border-radius:8px;padding:14px 16px;font-size:12px;line-height:1.6;color:#475B63;margin-bottom:24px;">
                                Kata sandi awal di atas telah di-<em>hash</em> secara aman di dalam sistem sebagai modal awal login Anda. Aktifkan akun terlebih dahulu melalui tombol di bawah, lalu segera perbarui kata sandi Anda di menu <strong>Pengaturan</strong> setelah berhasil masuk.
                            </div>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 22px;">
                                <tr>
                                    <td align="center" style="border-radius:8px;background-color:#087F83;">
                                        <a href="{{ $activationUrl }}" style="display:inline-block;padding:12px 24px;font-size:14px;font-weight:600;color:#FFFFFF;text-decoration:none;border-radius:8px;">
                                            Aktifkan Akun Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0;font-size:12px;line-height:1.6;color:#627680;">
                                Tautan aktivasi berlaku selama <strong>24 jam</strong>. Jika tombol di atas tidak berfungsi, salin tautan berikut ke peramban Anda:<br>
                                <a href="{{ $activationUrl }}" style="color:#087F83;word-break:break-all;">{{ $activationUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#FAFCFC;border-top:1px solid #DDE8E8;padding:16px 28px;font-size:12px;color:#627680;line-height:1.5;">
                            Email ini dikirim otomatis oleh sistem KLINIKA. Jangan bagikan kata sandi awal atau tautan aktivasi ini kepada pihak lain.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
