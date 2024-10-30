<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function createDefinedId($data)
    {
        $key = config('app.encryption_key');
        $iv = random_bytes(16);
        $encryptedData = openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        $encryptedDataWithIV = $iv . $encryptedData;
        $safeEncryptedData = str_replace('%', '0', urlencode(base64_encode($encryptedDataWithIV)));
        $trimmed = substr($safeEncryptedData, 0, 16);
        return $trimmed;
    }

    function encrypt($date, $length)
    {
        // Enkripsi tanggal menggunakan Base64
        $encryptedDate = hash('sha256', $date);

        // Ambil 5 karakter pertama sebagai bagian dari nama file
        $encryptedDate = substr($encryptedDate, 0, $length);

        return $encryptedDate;
    }

    function generateFilename($ext, $fileName, $uniqueID)
    {
        $tanggal = now()->format('YmdHisv');
        $encryptedDate = $this->encrypt($tanggal, 5);
        $encryptedId = $this->encrypt($uniqueID, 3);

        // Ganti spasi kosong dengan karakter _
        $fileName = str_replace(' ', '_', $fileName);

        // Buat nama file dengan format yang diinginkan
        $newFilename = "{$fileName}_{$encryptedId}_{$encryptedDate}.{$ext}";

        return $newFilename;
    }

    protected function generateIdLength($value, $digits): string
    {
        // Mengonversi nilai menjadi string
        $value = (string) $value;

        // Menghitung selisih antara panjang nilai dan jumlah digit yang diinginkan
        $padding = $digits - strlen($value);

        // Menambahkan padding nol jika perlu
        if ($padding > 0) {
            $value = str_repeat('0', $padding) . $value;
        }

        return $value; // value:string 
    }
}
