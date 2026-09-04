<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_type',
        'target_url',
        'target_name',
        'reason',
        'description',
        'reporter_email',
        'reporter_ip',
        'status',
        'admin_notes',
    ];

    public static function reasonLabels(): array
    {
        return [
            'penipuan'     => 'Penipuan / Fake Store',
            'hak_cipta'    => 'Pelanggaran Hak Cipta / Brand',
            'konten_ilegal' => 'Konten Ilegal / Melanggar Hukum',
            'spam'         => 'Spam / SARA / Ujaran Kebencian',
            'lainnya'      => 'Lainnya',
        ];
    }

    public function getReasonLabelAttribute(): string
    {
        return self::reasonLabels()[$this->reason] ?? ucfirst($this->reason);
    }
}
