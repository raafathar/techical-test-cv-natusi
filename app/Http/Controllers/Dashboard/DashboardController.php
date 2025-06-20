<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BreadCrumb;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\BaseDashboardController;

class DashboardController extends BaseDashboardController
{
    function index(Request $request)
    {
        try {

                $today = Carbon::today();
                $month = Carbon::now()->startOfMonth();
                $year = Carbon::now()->startOfYear();

                // Helper query function
                $baseQuery = function ($startDate) {
                    return Transaction::whereDate('created_at', '>=', $startDate)
                        ->with('details') // eager load
                        ->get()
                        ->map(function ($trx) {
                            $trx->total = $trx->details->sum(function ($detail) {
                                return $detail->qty * $detail->price;
                            });
                            return $trx;
                        });
                };

                $topDrugs = DetailTransaction::select('drug_id', DB::raw('SUM(qty) as total_qty'))
                    ->groupBy('drug_id')
                    ->orderByDesc('total_qty')
                    ->with('drug') // eager load relasi ke tabel drugs
                    ->take(10)
                    ->get();

                $transactionsToday = $baseQuery($today);
                $transactionsThisMonth = $baseQuery($month);
                $transactionsThisYear = $baseQuery($year);
                $transactionsAll = $baseQuery('2000-01-01'); // sejak awal

                $data = [
                    'today' => [
                        'count' => $transactionsToday->count(),
                        'total' => $transactionsToday->sum('total'),
                    ],
                    'month' => [
                        'count' => $transactionsThisMonth->count(),
                        'total' => $transactionsThisMonth->sum('total'),
                    ],
                    'year' => [
                        'count' => $transactionsThisYear->count(),
                        'total' => $transactionsThisYear->sum('total'),
                    ],
                    'all' => [
                        'count' => $transactionsAll->count(),
                        'total' => $transactionsAll->sum('total'),
                    ],
                    'top_drugs' => $topDrugs
                ];

            $this->setTitle('Dashboard');
            $this->setLayout(Session::get('layout', 'layouts.dashboard.vertical'));
            return $this->renderView('dashboard.index', ['data' => $data]);
        } catch (\Throwable $th) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage());
        }
    }
}
