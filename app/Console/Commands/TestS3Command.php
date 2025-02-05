<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestS3Command extends Command
{
    protected $signature = 'test:s3';
    protected $description = 'Test S3 configuration';

    public function handle()
    {
        $this->info('Testing S3 configuration...');

        try {
            // Try to create a test file
            Storage::disk('s3')->put('test.txt', 'Hello S3!');
            $this->info('✓ Successfully uploaded test file');

            // Try to read the file
            $contents = Storage::disk('s3')->get('test.txt');
            $this->info('✓ Successfully read test file: ' . $contents);

            // Clean up
            Storage::disk('s3')->delete('test.txt');
            $this->info('✓ Successfully deleted test file');

            $this->info('All S3 operations completed successfully!');
        } catch (\Exception $e) {
            $this->error('S3 test failed: ' . $e->getMessage());
        }
    }
}
