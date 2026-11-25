<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Formulir Kontak KLINIKA</title>
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
                            <p style="margin:4px 0 0;font-size:13px;color:#E1F0ED;">Pesan Baru dari Formulir Kontak Landing Page</p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px;">
                            <div style="display:inline-block;background-color:#E8F5F3;color:#087F83;font-size:12px;font-weight:600;padding:5px 12px;border-radius:999px;margin-bottom:20px;">
                                {{ $topicLabel }}
                            </div>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:22px;">
                                <tr>
                                    <td style="padding:8px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#627680;width:160px;vertical-align:top;">Nama Lengkap</td>
                                    <td style="padding:8px 0;border-bottom:1px solid #EEF3F3;font-size:13px;font-weight:600;color:#182B35;">{{ $inquiry['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#627680;vertical-align:top;">Klinik / Instansi</td>
                                    <td style="padding:8px 0;border-bottom:1px solid #EEF3F3;font-size:13px;font-weight:600;color:#182B35;">{{ $inquiry['clinic_name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#627680;vertical-align:top;">Email Pengirim</td>
                                    <td style="padding:8px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#087F83;">
                                        <a href="mailto:{{ $inquiry['email'] }}" style="color:#087F83;text-decoration:none;font-weight:600;">{{ $inquiry['email'] }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#627680;vertical-align:top;">WhatsApp / Telepon</td>
                                    <td style="padding:8px 0;border-bottom:1px solid #EEF3F3;font-size:13px;color:#182B35;">{{ $inquiry['phone'] ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 0;font-size:13px;color:#627680;vertical-align:top;">Waktu Kirim</td>
                                    <td style="padding:8px 0;font-size:13px;color:#627680;">{{ $inquiry['submitted_at'] }}</td>
                                </tr>
                            </table>

                            <p style="margin:0 0 8px;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:#627680;">Isi Pesan</p>
                            <div style="background-color:#FAFCFC;border:1px solid #DDE8E8;border-radius:8px;padding:16px;font-size:14px;line-height:1.6;color:#182B35;">
                                {!! nl2br(e($inquiry['message'])) !!}
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#FAFCFC;border-top:1px solid #DDE8E8;padding:16px 28px;font-size:12px;color:#627680;line-height:1.5;">
                            Email ini dikirim otomatis dari formulir kontak KLINIKA. Anda dapat langsung menekan <strong>Reply</strong> untuk membalas ke <strong>{{ $inquiry['email'] }}</strong>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
