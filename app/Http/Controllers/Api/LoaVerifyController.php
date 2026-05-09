<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoaValidated;
use Illuminate\Http\JsonResponse;

class LoaVerifyController extends Controller
{
    /**
     * GET /api/v1/loa/{code}
     * Verifikasi LOA berdasarkan kode. Dapat diintegrasikan sistem kampus/jurnal eksternal.
     */
    public function verify(string $code): JsonResponse
    {
        $loa = LoaValidated::where('loa_code', $code)
            ->with(['loaRequest.journal.publisher'])
            ->first();

        if (!$loa) {
            return response()->json([
                'valid'    => false,
                'message'  => 'Kode LOA tidak ditemukan.',
                'loa_code' => $code,
            ], 404);
        }

        $req       = $loa->loaRequest;
        $journal   = $req?->journal;
        $publisher = $journal?->publisher;

        return response()->json([
            'valid'      => true,
            'loa_code'   => $loa->loa_code,
            'signed_at'  => $loa->signed_at?->toISOString(),
            'verified_at'=> now()->toISOString(),
            'article'    => [
                'title'        => $req?->article_title,
                'author'       => $req?->author,
                'author_email' => $req?->author_email,
                'volume'       => $req?->volume,
                'number'       => $req?->number,
                'month'        => $req?->month,
                'year'         => $req?->year,
            ],
            'journal'    => [
                'name'   => $journal?->name,
                'e_issn' => $journal?->e_issn,
                'p_issn' => $journal?->p_issn,
                'sinta_id'           => $journal?->sinta_id,
                'accreditation_level'=> $journal?->accreditation_level,
            ],
            'publisher'  => [
                'name'    => $publisher?->name,
                'email'   => $publisher?->email,
                'website' => $publisher?->website,
            ],
            'digital_signature' => $loa->digital_signature ? [
                'hash'      => $loa->digital_signature,
                'algorithm' => 'HMAC-SHA256',
                'signed_by' => 'LOA SIPTENAN System',
            ] : null,
        ]);
    }
}
