<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityExport implements FromCollection, WithHeadings
{
    protected $activities;

    public function __construct($activities)
    {
        $this->activities = $activities;
    }

    public function collection()
    {
        return $this->activities->map(function ($activity) {
            return [
                'User' => $activity->user->name,
                'Role' => $activity->user->role,
                'Activity' => $activity->activity,
                'Time' => $activity->created_at->setTimezone('Asia/Jakarta')->format('H-i-s'),
                'Date' => $activity->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'User',
            'Role',
            'Activity',
            'Time',
            'Date',
        ];
    }
}