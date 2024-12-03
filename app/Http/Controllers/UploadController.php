<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadChunkRequest;
use App\Jobs\ProcessFileUpload;
use App\Models\FileUpload;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class UploadController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Upload a file chunk
     *
     * @param UploadChunkRequest $request
     * @return JsonResponse
     */
    public function uploadChunk(UploadChunkRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $filename = $validated['filename'];
        $chunkIndex = $validated['chunkIndex'];
        $totalChunks = $validated['totalChunks'];
        $timestamp = $validated['timestamp'];
        $uniqueFileName = $timestamp."_".$filename;

        // Get the uploaded chunk
        $chunk = $request->file('file');

        try {
            // Handle the chunk upload through the service
            $this->fileUploadService->handleChunkUpload($uniqueFileName, $chunk, $chunkIndex, $totalChunks);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upload chunk.'], 500);
        }

        // Return a response based on the chunk upload status
        if ($chunkIndex + 1 === $totalChunks) {
            return response()->json(['message' => 'File upload complete!'], 200);
        }

        return response()->json(['message' => "Chunk {$chunkIndex} uploaded successfully."]);
    }

    public function uploadProgress($filename): JsonResponse
    {
        $fileUpload = FileUpload::where('filename', $filename)->first();

        if ($fileUpload) {
            $progress = ($fileUpload->chunk_count / $fileUpload->total_chunks) * 100;
            return response()->json(['progress' => $progress]);
        }

        return response()->json(['progress' => 0]);
    }
}
