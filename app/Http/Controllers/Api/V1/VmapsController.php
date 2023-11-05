<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Vmap;
use Illuminate\Http\Request;

class VmapsController extends Controller
{
    public function show(Vmap $vmap, Request $request)
    {
        $duration = match ($request->size) {
            'sm' => 30 * 60, // 30 minutes
            'md' => 1 * 60 * 60, // 1 hour
            'lg' => 2 * 60 * 60, // 2 hours
            'xl' => 3 * 60 * 60, // 3 hours
            'xxl' => 4 * 60 * 60, // 4 hours
            'xxxl' => 5 * 60 * 60, // 5 hours
            default => 1.5 * 60 * 60, // 1.5 hours
        };

        $prerolls = [];
        $midrolls = [];
        $postrolls = [];

        $midrollCount = 0;
        foreach ($vmap->adBreaks as $adBreak) {
            if ($adBreak->category === 'preroll') {
                $prerolls[] = [
                    'id' => 'preroll-' . count($prerolls) + 1,
                    'vast_url' => $adBreak->vast_url,
                    'break_type' => $adBreak->break_type ?? 'linear',
                ];
            }else if ($adBreak->category === 'postroll') {
                $postrolls[] = [
                    'id' => 'postroll-' . count($postrolls) + 1,
                    'vast_url' => $adBreak->vast_url,
                    'break_type' => $adBreak->break_type ?? 'linear',
                ];
            }else{
                if ($adBreak->time_offset > 0) {
                    $midrolls[] = [
                        'id' => 'midroll-' . $midrollCount . 1 . '-ad-1',
                        'vast_url' => $adBreak->vast_url,
                        'break_type' => $adBreak->break_type ?? 'linear',
                        'time_offset' => gmdate('H:i:s', $adBreak->time_offset),
                        'time_offset_seconds' => $adBreak->time_offset,
                    ];
                }
                if ($adBreak->repeat_after > 0) {
                    $adCount = 0;
                    for (
                        $time_offset = ($adBreak->time_offset + $adBreak->repeat_after);
                        $time_offset <= $duration;
                        $time_offset += $adBreak->repeat_after
                    ) {
                        $midrolls[] = [
                            'id' => 'midroll-' . $midrollCount . 1 . '-ad-' . $adCount + 1,
                            'vast_url' => $adBreak->vast_url,
                            'break_type' => $adBreak->break_type ?? 'linear',
                            'time_offset' => gmdate('H:i:s', $time_offset),
                            'time_offset_seconds' => $time_offset,
                        ];
                        $adCount++;
                    }
                }
                if ($adBreak->time_offset > 0 || $adBreak->repeat_after > 0) {
                    $midrollCount++;
                }
            }
        }

        if ($midrollCount > 1) {
            usort($postrolls, function ($a, $b) {
                return $a['time_offset_seconds'] <=> $b['time_offset_seconds'];
            });
        }

        return response()
            ->view('vmaps.show', [
                'prerolls' => $prerolls,
                'midrolls' => $midrolls,
                'postrolls' => $postrolls,
            ])
            ->header('Content-Type', 'text/xml');
    }
}
