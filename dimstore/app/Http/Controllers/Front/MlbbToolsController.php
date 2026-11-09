<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class MlbbToolsController extends Controller
{
    public function inquiryNickname(Request $request)
    {
        $validated = $request->validate(
            [
                'user_id'   => ['required', 'regex:/^[0-9]{6,12}$/'],
                'server_id' => ['required', 'regex:/^[0-9]{3,6}$/'],
            ],
            [
                'user_id.required'   => 'User ID wajib diisi.',
                'user_id.regex'      => 'User ID harus 6–12 digit angka.',
                'server_id.required' => 'Zone ID wajib diisi.',
                'server_id.regex'    => 'Zone ID harus 3–6 digit angka.',
            ]
        );

        // Konfigurasi provider (isi di config/services.php + .env)
        $endpoint = (string) config('services.mlbb.inquiry_url', '');
        $apiKey   = (string) config('services.mlbb.api_key', '');
        $secret   = (string) config('services.mlbb.secret', ''); // optional, tergantung provider

        // Jika belum diset, balikin response aman (UI tetap jalan)
        if ($endpoint === '' || $apiKey === '') {
            return response()->json([
                'ok' => true,
                'nickname' => null,
                'message' => 'Format valid, tapi fitur nickname butuh API provider (inquiry) agar bisa tampil nama player.',
            ]);
        }

        $userId = $validated['user_id'];
        $zoneId = $validated['server_id'];

        /**
         * Banyak provider topup minta salah satu:
         * 1) user_id + server_id
         * 2) customer_no: "USERID|ZONEID" atau "USERID(ZONEID)"
         *
         * Aku kirim dua-duanya supaya kompatibel.
         */
        $payload = [
            'user_id'     => $userId,
            'server_id'   => $zoneId,
            'customer_no' => "{$userId}|{$zoneId}",
        ];

        try {
            /** @var PendingRequest $http */
            $http = Http::timeout(12)->acceptJson();

            /**
             * Header provider beda-beda:
             * - Ada yang pakai Authorization: Bearer
             * - Ada yang pakai X-API-KEY
             * Aku set dua-duanya (yang tidak dipakai biasanya diabaikan provider).
             */
            $http = $http->withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-KEY'     => $apiKey,
            ]);

            // kalau provider butuh secret/signature, biasanya masuk payload atau header.
            // (kalau tidak butuh, aman diabaikan)
            if ($secret !== '') {
                $payload['secret'] = $secret;
            }

            /** @var Response $res */
            $res  = $http->post($endpoint, $payload);
            $json = $res->json();

            // Kalau provider balikin error HTTP
            if (! $res->successful()) {
                return response()->json([
                    'ok' => false,
                    'nickname' => null,
                    'message' => data_get($json, 'message')
                        ?? data_get($json, 'error')
                        ?? 'Gagal inquiry ke provider.',
                ], 422);
            }

            /**
             * Parsing nickname (struktur provider beda-beda).
             * Tambahkan key lain kalau provider kamu beda.
             */
            $nickname =
                data_get($json, 'data.nickname')
                ?? data_get($json, 'data.user.nickname')
                ?? data_get($json, 'data.name')
                ?? data_get($json, 'nickname')
                ?? data_get($json, 'user.nickname')
                ?? data_get($json, 'player_name')
                ?? data_get($json, 'data.player_name')
                ?? data_get($json, 'data.username')
                ?? data_get($json, 'username');

            $nickname = is_string($nickname) ? trim($nickname) : null;

            return response()->json([
                'ok' => true,
                'nickname' => $nickname ?: null,
                'message' => $nickname ? 'Nickname berhasil diambil.' : 'Nickname tidak ditemukan dari provider.',
                // 'raw' => $json, // aktifkan sementara kalau mau debug response provider
            ]);
        } catch (\Throwable $e) {
            Log::warning('MLBB inquiry nickname error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'server_id' => $zoneId,
                'endpoint' => $endpoint,
            ]);

            return response()->json([
                'ok' => false,
                'nickname' => null,
                'message' => 'Timeout / error saat inquiry: ' . $e->getMessage(),
            ], 500);
        }
    }
}
