<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifikasiKeKurir extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Tugas Pengambilan/Pengiriman Barang Baru')
                    ->greeting('Halo ' . $notifiable->name . ',')
                    ->line('Anda mendapatkan tugas untuk menangani barang "' . $this->data['nama_barang'] . '".')
                    ->line('Alamat tujuan: ' . $this->data['alamat_tujuan'])
                    ->line('Jadwal: ' . $this->data['jadwal'])
                    ->line('Silakan periksa dashboard Anda untuk detail lebih lanjut.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Tugas Baru',
            'message' => 'Anda ditugaskan untuk mengirim/mengambil barang "' . $this->data['nama_barang'] . '" pada ' . $this->data['jadwal'],
            'alamat_tujuan' => $this->data['alamat_tujuan']
        ];
    }
}
