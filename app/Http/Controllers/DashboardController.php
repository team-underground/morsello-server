<?php

namespace App\Http\Controllers;

use App\Bit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $months = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];
        $result = Bit::query()->where('user_id', auth()->id())->select([
            DB::raw('count(id) as `total`'),
            DB::raw("DATE_FORMAT(created_at, '%M') as month")
        ])->groupBy('month')->orderBy('month')->pluck('total', 'month')->all();

        $data = [];

        foreach ($months as $key => $month) {
            if (array_key_exists($month, $result)) {
                $data[$month] = $result[$month];
            } else {
                $data[$month] = 0;
            }
        }

        return response([
            'data' => array_values($data),
            'snippets_count' => auth()->user()->bits->count(),
            'bookmarks_count' => auth()->user()->bookmarks->count()
        ]);
    }
}
