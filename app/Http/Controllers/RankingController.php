<?php

namespace App\Http\Controllers;

use App\Models\Histories;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RankingController extends Controller
{
    public function getRankings()
    {
        $selectedMonth = request('month', Carbon::now()->month);
        $selectedYear = request('year', Carbon::now()->year);

        $workSchedules = [
            'Monday'    => ['start' => '09:00', 'end' => '18:00'],
            'Tuesday'   => ['start' => '08:00', 'end' => '18:00'],
            'Wednesday' => ['start' => '08:00', 'end' => '18:00'],
            'Thursday'  => ['start' => '08:00', 'end' => '18:00'],
            'Friday'    => ['start' => '08:00', 'end' => '18:00'],
            'Saturday'  => ['start' => '08:00', 'end' => '12:00'],
            'Sunday'    => ['start' => null, 'end' => null],
        ];

        $groupedData = [];

        $users = User::all();

        foreach ($users as $user) {
            $userLogs = Histories::where('user_id', $user->id)
                ->whereYear('datetime', $selectedYear)
                ->whereMonth('datetime', $selectedMonth)
                ->orderBy('datetime', 'asc')
                ->get();

            $logsByDate = $userLogs->groupBy(fn($log) => Carbon::parse($log->datetime)->format('Y-m-d'));

            $daysInMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->daysInMonth;
            $totalHours = 0;
            $firstname = $user->firstname;

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dateKey = Carbon::createFromDate($selectedYear, $selectedMonth, $day)->format('Y-m-d');
                $dayName = Carbon::parse($dateKey)->format('l'); // Get day name (Monday, Tuesday, etc.)

                if (!isset($workSchedules[$dayName]) || is_null($workSchedules[$dayName]['start'])) {
                    continue; // Skip non-working days (like Sunday)
                }

                $workStart = Carbon::parse("$dateKey " . $workSchedules[$dayName]['start']);
                $workEnd = Carbon::parse("$dateKey " . $workSchedules[$dayName]['end']);

                $lunchStart = Carbon::parse("$dateKey 12:00");
                $lunchEnd = Carbon::parse("$dateKey 13:00");

                if (isset($logsByDate[$dateKey])) {
                    $logs = $logsByDate[$dateKey];
                    $firstTimeIn = null;
                    $lastTimeOut = null;
                    $dailyHours = 0;

                    foreach ($logs as $log) {
                        $logTime = Carbon::parse($log->datetime);

                        if ($log->description === 'time in' && !$firstTimeIn) {
                            $firstTimeIn = $logTime;
                        }

                        if ($log->description === 'time out') {
                            $lastTimeOut = $logTime;
                        }
                    }

                    if ($firstTimeIn && $lastTimeOut) {
                        if ($firstTimeIn->lt($workStart)) {
                            $firstTimeIn = $workStart;
                        }
                        if ($lastTimeOut->gt($workEnd)) {
                            $lastTimeOut = $workEnd;
                        }

                        $dailyMinutes = $firstTimeIn->diffInMinutes($lastTimeOut);

                        if ($firstTimeIn->lt($lunchEnd) && $lastTimeOut->gt($lunchStart)) {
                            $dailyMinutes -= $lunchStart->diffInMinutes($lunchEnd);
                        }

                        $dailyHours = $dailyMinutes / 60;
                        $totalHours += $dailyHours;
                    }
                }
            }

            $groupedData[$user->id] = [
                'schools' => $user->schools,
                'profiles' => $user->profiles,
                'role' => $user->role,
                'name' => $firstname,
                'user_id' => $user->id,
                'hours_worked' => floor($totalHours),
            ];
        }

        $topUsers = collect($groupedData)
            ->sortByDesc('hours_worked')
            ->take(3)
            ->values()
            ->toArray();

        return $topUsers;
    }
}
