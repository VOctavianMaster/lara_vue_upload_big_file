<?php

namespace App\Services;

use App\Models\FileUpload;
use App\Jobs\ProcessFileUpload;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    /**
     * Handle the chunk upload process
     *
     * @param string $filename
     * @param UploadedFile $chunk
     * @param int $chunkIndex
     * @param int $totalChunks
     * @return void
     * @throws \Exception
     */
    public function handleChunkUpload(string $filename, UploadedFile $chunk, int $chunkIndex, int $totalChunks): void
    {
        $temporaryPath = storage_path("app/tmp/{$filename}.part");

        // Ensure the temporary directory exists
        $this->ensureTemporaryDirectory();

        // Append the chunk to the temporary file
        $this->appendChunkToTemporaryFile($temporaryPath, $chunk);

        // Track upload progress
        $this->trackProgress($filename, $chunkIndex, $totalChunks);

        // If it's the last chunk, dispatch the job
        if ($chunkIndex + 1 === $totalChunks) {
            ProcessFileUpload::dispatch($filename, $temporaryPath, storage_path("app/uploads/{$filename}"));
        }
    }

    /**
     * Ensure the temporary directory exists
     *
     * @return void
     */
    protected function ensureTemporaryDirectory(): void
    {
        $temporaryDirectory = storage_path('app/tmp');
        if (!is_dir($temporaryDirectory)) {
            mkdir($temporaryDirectory, 0755, true); // Ensure the directory is created with proper permissions
        }
    }

    /**
     * Append the chunk data to the temporary file
     *
     * @param string $temporaryPath
     * @param UploadedFile $chunk
     * @return void
     */
    protected function appendChunkToTemporaryFile(string $temporaryPath, UploadedFile $chunk): void
    {
        $chunkData = file_get_contents($chunk->getRealPath());
        file_put_contents($temporaryPath, $chunkData, FILE_APPEND); // Append the chunk to the temporary file
    }

    /**
     * Track the upload progress in the database
     *
     * @param string $filename
     * @param int $chunkIndex
     * @param int $totalChunks
     * @return void
     */
    protected function trackProgress(string $filename, int $chunkIndex, int $totalChunks): void
    {
        FileUpload::updateOrCreate(
            ['filename' => $filename],
            ['chunk_count' => $chunkIndex + 1, 'total_chunks' => $totalChunks]
        );
    }
}
