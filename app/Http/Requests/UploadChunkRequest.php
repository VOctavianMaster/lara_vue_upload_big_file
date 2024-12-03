<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadChunkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|max:5120', // Max 5 MB for chunk
            'filename' => 'required|string|max:255',
            'chunkIndex' => 'required|integer|min:0',
            'totalChunks' => 'required|integer|min:1',
            'timestamp' => 'required|integer',
        ];
    }
    /**
     * Validation Messages.
     */
    public function messages()
    {
        return [
            'file.required' => 'The file is required for upload.',
            'file.max' => 'The file chunk size cannot exceed 5MB.',
            'filename.required' => 'The filename is required.',
            'chunkIndex.required' => 'The chunk index is required.',
            'totalChunks.required' => 'The total chunks count is required.',
        ];
    }
}
