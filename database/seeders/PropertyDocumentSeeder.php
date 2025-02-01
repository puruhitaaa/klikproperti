<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyDocument;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertyDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first();
        $properties = Property::all();

        $documentTypes = [
            'sertifikat' => 'Sertifikat Hak Milik (SHM)',
            'imb' => 'Izin Mendirikan Bangunan (IMB)',
            'pbb' => 'Pajak Bumi dan Bangunan (PBB)',
            'akta' => 'Akta Jual Beli',
            'sppt' => 'Surat Pemberitahuan Pajak Terutang'
        ];

        foreach ($properties as $property) {
            foreach ($documentTypes as $type => $title) {
                $status = ['pending', 'verified', 'rejected'][rand(0, 2)];

                PropertyDocument::create([
                    'property_id' => $property->id,
                    'document_type' => $type,
                    'title' => $title,
                    'description' => 'Dokumen ' . $title . ' untuk properti ' . $property->title,
                    'status' => $status,
                    'rejection_reason' => $status === 'rejected' ? 'Dokumen tidak lengkap atau tidak jelas' : null,
                    'verified_at' => $status === 'verified' ? now() : null,
                    'verified_by' => $status === 'verified' ? $admin->id : null,
                ]);
            }
        }
    }
}
