<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditService
{
    public function log(string $action, string $refType, int $refId, array $meta = [], ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'reference_type' => $refType,
            'reference_id' => $refId,
            'meta' => $meta,
            'created_at' => now(),
        ]);
    }
}