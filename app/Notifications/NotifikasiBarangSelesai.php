<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifikasiBarangSelesai extends Notification
{
    protected $barang;
    protected $untuk;

    public function __construct($barang, $untuk)
    {
        $this->barang = $barang;
        $this->untuk = $untuk;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // Tentukan pesan berdasarkan $this->untuk
        if ($this->untuk === 'penitip') {
            $pesan = 'Barang "' . $this->barang->nama . '" telah berhasil diambil oleh pembeli.';
        } elseif ($this->untuk === 'pembeli') {
            $pesan = 'Kamu telah berhasil mengambil barang "' . $this->barang->nama . '".';
        } else {
            $pesan = 'Status pengambilan barang "' . $this->barang->nama . '" telah diperbarui.';
        }

        $url = url('/' . $this->untuk . '/barang/' . $this->barang->id);

        return [
            'barang_id' => $this->barang->id,
            'nama_barang' => $this->barang->nama,
            'status' => $this->barang->status,
            'pesan' => $pesan,
            'url' => $url,
        ];
    }
}

