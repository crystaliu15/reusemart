<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Barang;
use Carbon\Carbon;

class JadwalPengambilanDibuat extends Notification
{
    protected $barang;

    public function __construct($barang)
    {
        $this->barang = $barang;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $jadwalPengambilan = $this->barang->jadwalPengambilan->jadwal_pengambilan ?? null;
        $formattedJadwal = $jadwalPengambilan ? Carbon::parse($jadwalPengambilan)->format('d-m-Y H:i') : 'Belum Dijadwalkan';

        return [
            'barang_id' => $this->barang->id,
            'nama_barang' => $this->barang->nama,
            'jadwal_pengambilan' => $formattedJadwal,
            'pesan' => "Jadwal pengambilan untuk barang '{$this->barang->nama}' telah dijadwalkan pada {$formattedJadwal}.",
            'url' => url('/penitip/barang/' . $this->barang->id),
        ];
    }
}
