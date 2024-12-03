<?php

namespace App\Jobs;

use App\Models\FileUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessFileUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filename;
    protected $temporaryPath;
    protected $finalPath;

    // Constructor to receive data
    public function __construct($filename, $temporaryPath, $finalPath)
    {
        $this->filename = $filename;
        $this->temporaryPath = $temporaryPath;
        $this->finalPath = $finalPath;
    }

    public function handle()
    {
        $finalFolder = dirname($this->finalPath);
        if (!is_dir($finalFolder)) {
            mkdir($finalFolder, 0755, true); // Create the 'tmp' directory with proper permissions
        }
        // Move the temporary file to the final location
        rename($this->temporaryPath, $this->finalPath);

        // Update the database to reflect the file's completion
        FileUpload::where('filename', $this->filename)->update(['chunk_count' => 0]);

        // Optionally, process the file further (e.g., extract data, generate thumbnails)
        // Further file processing logic can be added here
    }
}
