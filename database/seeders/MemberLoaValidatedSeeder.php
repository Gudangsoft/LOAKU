<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoaValidated;
use App\Models\LoaRequest;

class MemberLoaValidatedSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get approved LOA requests that don't have validated LOAs yet
        $approvedRequests = LoaRequest::where('status', 'approved')
            ->whereDoesntHave('loaValidated')
            ->get();

        if ($approvedRequests->count() === 0) {
            $this->command->warn('No approved LOA requests found without validated LOAs.');
            return;
        }

        foreach ($approvedRequests as $request) {
            // Generate unique LOA code
            $loaCode = 'LOA' . date('Y') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            
            // Make sure code is unique
            while (LoaValidated::where('loa_code', $loaCode)->exists()) {
                $loaCode = 'LOA' . date('Y') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            }

            LoaValidated::create([
                'loa_request_id' => $request->id,
                'loa_code' => $loaCode,
                'pdf_path_id' => 'loa_pdfs/' . $loaCode . '_id.pdf',
                'pdf_path_en' => 'loa_pdfs/' . $loaCode . '_en.pdf',
                'verification_url' => url('/loa/verify/' . $loaCode),
                'created_at' => $request->approved_at ?? now(),
                'updated_at' => $request->approved_at ?? now(),
            ]);
        }

        $this->command->info('Created ' . $approvedRequests->count() . ' validated LOAs for approved requests.');
    }
}
