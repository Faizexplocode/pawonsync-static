<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class SupabaseStorage
{
    public static function upload(UploadedFile $file, string $folder): string
    {
        $disk = config('filesystems.disks.supabase', []);

        $url = rtrim((string) ($disk['url'] ?? ''), '/');
        $key = (string) ($disk['key'] ?? '');
        $bucket = (string) ($disk['bucket'] ?? 'pawonsync');

        if ($url === '' || $key === '' || $bucket === '') {
            return self::simpanLokal($file, $folder);
        }

        $path = $folder.'/'.Str::uuid().'.'.$file->getClientOriginalExtension();

        $response = Http::withToken($key)
            ->withHeaders(['x-upsert' => 'true'])
            ->attach('file', file_get_contents($file->getRealPath()), $path, [
                'Content-Type' => $file->getMimeType() ?? 'application/octet-stream',
            ])
            ->post("{$url}/storage/v1/object/{$bucket}/{$path}");

        if ($response->failed()) {
            throw new RuntimeException('Gagal mengunggah foto ke Supabase Storage: '.$response->body());
        }

        return "{$url}/storage/v1/object/public/{$bucket}/{$path}";
    }

    private static function simpanLokal(UploadedFile $file, string $folder): string
    {
        $path = $file->store($folder, 'public');

        if ($path === false) {
            throw new RuntimeException('Gagal menyimpan foto.');
        }

        return Storage::disk('public')->url($path);
    }
}
