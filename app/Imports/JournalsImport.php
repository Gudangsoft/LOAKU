<?php

namespace App\Imports;

use App\Models\Journal;
use App\Models\Publisher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JournalsImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $errors = [];
    protected $successCount = 0;
    protected $publisherId;

    public function __construct($publisherId = null)
    {
        $this->publisherId = $publisherId;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $index => $row) {
            try {
                $rowNumber = $index + 2; // +2 because index starts at 0 and we have header row
                
                // Validate required fields
                if (empty($row['nama_jurnal'])) {
                    $this->errors[] = "Baris {$rowNumber}: Nama jurnal wajib diisi";
                    continue;
                }

                // Find or get publisher
                $publisherId = $this->publisherId;
                if (!$publisherId && !empty($row['publisher_email'])) {
                    $publisher = Publisher::where('email', $row['publisher_email'])->first();
                    if ($publisher) {
                        $publisherId = $publisher->user_id;
                    }
                }

                if (!$publisherId) {
                    $publisherId = Auth::id();
                }

                // Check if journal already exists
                $existingJournal = Journal::where('name', $row['nama_jurnal'])
                    ->where('user_id', $publisherId)
                    ->first();

                if ($existingJournal) {
                    // Update existing journal
                    $existingJournal->update([
                        'description' => $row['deskripsi'] ?? $existingJournal->description,
                        'issn' => $row['issn'] ?? $existingJournal->issn,
                        'e_issn' => $row['e_issn'] ?? $existingJournal->e_issn,
                        'website' => $row['website'] ?? $existingJournal->website,
                        'email' => $row['email'] ?? $existingJournal->email,
                        'address' => $row['alamat'] ?? $existingJournal->address,
                        'status' => $this->mapStatus($row['status'] ?? 'active'),
                    ]);
                } else {
                    // Create new journal
                    Journal::create([
                        'name' => $row['nama_jurnal'],
                        'description' => $row['deskripsi'] ?? '',
                        'issn' => $row['issn'] ?? '',
                        'e_issn' => $row['e_issn'] ?? '',
                        'website' => $row['website'] ?? '',
                        'email' => $row['email'] ?? '',
                        'address' => $row['alamat'] ?? '',
                        'user_id' => $publisherId,
                        'status' => $this->mapStatus($row['status'] ?? 'active'),
                    ]);
                }

                $this->successCount++;

            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNumber}: " . $e->getMessage();
            }
        }
    }

    private function mapStatus($status)
    {
        $status = strtolower(trim($status));
        $validStatuses = ['active', 'inactive', 'pending'];
        
        return in_array($status, $validStatuses) ? $status : 'active';
    }

    public function rules(): array
    {
        return [
            'nama_jurnal' => 'required|string|max:255',
            'issn' => 'nullable|string|max:20',
            'e_issn' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }
}
