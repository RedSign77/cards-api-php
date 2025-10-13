<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompletedJob extends Model
{
    protected $table = 'completed_jobs';

    public $timestamps = false;

    protected $fillable = [
        'uuid',
        'connection',
        'queue',
        'payload',
        'job_class',
        'completed_at',
        'attempts',
        'execution_time',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'completed_at' => 'datetime',
            'attempts' => 'integer',
            'execution_time' => 'integer',
        ];
    }

    /**
     * Get the decoded job data from payload
     */
    public function getJobDataAttribute()
    {
        if (isset($this->payload['data'])) {
            $command = unserialize($this->payload['data']['command']);
            return $command;
        }

        return null;
    }

    /**
     * Get human-readable execution time
     */
    public function getExecutionTimeHumanAttribute()
    {
        if (!$this->execution_time) {
            return 'N/A';
        }

        if ($this->execution_time < 1000) {
            return $this->execution_time . ' ms';
        }

        return round($this->execution_time / 1000, 2) . ' sec';
    }
}
