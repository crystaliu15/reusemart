<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Barang;
use Carbon\Carbon;

class NotifikasiKePenitip extends Notification
{
    protected $barang;

    public function __construct(Barang $barang)
    {
        $this->barang = $barang;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $jadwalKirim = $this->barang->jadwalPengirimen->jadwal_kirim ?? null;

        return (new MailMessage)
            ->subject('Jadwal Pengiriman Barang Anda')
            ->line('Barang: ' . $this->barang->nama)
            ->line('Jadwal Kirim: ' . ($jadwalKirim ? Carbon::parse($jadwalKirim)->format('d-m-Y H:i') : 'Belum Dijadwalkan'))
            ->action('Lihat Detail', url('/pembeli/barang/' . $this->barang->id));
    }

    public function toDatabase($notifiable)
    {
        $jadwalKirim = $this->barang->jadwalPengirimen->jadwal_kirim ?? null;
        $formattedJadwal = $jadwalKirim ? Carbon::parse($jadwalKirim)->format('d-m-Y H:i') : 'Belum Dijadwalkan';

        return [
            'barang_id' => $this->barang->id,
            'nama_barang' => $this->barang->nama,
            'jadwal_kirim' => $formattedJadwal,
            'url' => url('/pembeli/barang/' . $this->barang->id),
            'pesan' => "Jadwal pengiriman untuk barang '{$this->barang->nama}' telah dijadwalkan pada {$formattedJadwal}.",
        ];
    }

}
