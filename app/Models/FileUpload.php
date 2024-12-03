<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $filename
 * @property int $total_chunks
 * @property int $chunk_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload whereChunkCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload whereTotalChunks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileUpload whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FileUpload extends Model
{
    protected $table = 'file_uploads';

    protected $fillable = [
        'filename',
        'total_chunks',
        'chunk_count',
    ];
}
