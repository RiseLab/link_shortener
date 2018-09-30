<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Shortlink;
use App\Click;
use App\Custom\PseudoCrypt;

class ShortlinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return Shortlink::select('id', 'url', 'hash')
			->where('user_id', \Auth::user()->id)
			->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'url' => 'required|url',
		]);

		if ($validator->fails()){
			return response()->json(['error' => $validator->errors()->first('url')], 400);
		}

		$shortlink = Shortlink::create([
			'url' => $request->url,
			'user_id' => \Auth::user()->id,
		]);

		$shortlink->hash = PseudoCrypt::hash($shortlink->id);

		$shortlink->save();

		return response()->json($shortlink, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$shortlink = Shortlink::select('id', 'url', 'hash')
			->where('user_id', \Auth::user()->id)
			->where('id', $id)
			->withCount('clicks')
			->first();

		if (!is_null($shortlink)){
			return $shortlink;
		} else {
			return response()->json(['error' => 'Link not found.'], 400);
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$shortlink = Shortlink::where('user_id', \Auth::user()->id)
			->where('id', $id)
			->first();

		if (!is_null($shortlink)){
			$shortlink->delete();
			return response()->json(null, 204);
		} else {
			return response()->json(['error' => 'Link not found.'], 400);
		}
    }

	/**
	 * Redirect to link by passed hash.
	 *
	 * @param $hash
	 * @return mixed
	 */
    public function click($hash)
	{
		$shortlink = Shortlink::where('hash', $hash)
			->first();

		if (!is_null($shortlink)){
			Click::create([
				'shortlink_id' => $shortlink->id,
				'referer' => request()->headers->get('referer'),
			]);
			return \Redirect::to($shortlink->url);
		} else {
			return \Redirect::to('/');
		}
	}

	/**
	 * Get top referrers for the shortlink.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function reportReferers($id)
	{
		$shortlink = Shortlink::where('user_id', \Auth::user()->id)
			->where('id', $id)
			->first();

		if (is_null($shortlink)){
			return response()->json(['error' => 'Link not found.'], 400);
		}

		return Click::select('referer', DB::raw('COUNT(*) as clicks_count'))
			->where('shortlink_id', $id)
			->groupBy('referer')
			->orderBy('clicks_count', 'desc')
			->take(20)
			->get();
	}

	/**
	 * Get clicks time graph for the link with grouping by days, hours, minutes
	 *
	 * @param $id
	 * @param $interval
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function reportTimeGraph($id, $interval)
	{
		// TODO: check and optimize code

		$intervals = [
			'days' => '00:00',
			'hours' => '%H:00',
			'mins' => '%H:%i',
		];

		// Check if link exists for current user
		$shortlink = Shortlink::where('user_id', \Auth::user()->id)
			->where('id', $id)
			->first();

		if (is_null($shortlink)){
			return response()->json(['error' => 'Link not found.'], 400);
		}

		// Check correct interval type
		if (!array_key_exists($interval, $intervals)){
			return response()->json(['error' => 'Incorrect interval type.'], 400);
		}

		// Check correct interval dates format
		$validator = Validator::make(request()->all(), [
			'from_date' => 'date_format:Y-m-d',
			'to_date' => 'date_format:Y-m-d',
		]);

		if ($validator->fails()){
			return response()->json(['error' => $validator->errors()->first()], 400);
		}

		// Set interval dates values
		$dateFrom = request('from_date') ?: '1970-01-01';
		$dateTo = request('to_date') ?: date('Y-m-d');

		return Click::select(DB::raw('DATE_FORMAT(created_at, \'%Y-%m-%d ' . $intervals[$interval] . ':00\') as date_point, COUNT(*) as clicks_count'))
			->where('shortlink_id', $id)
			->whereBetween('created_at', [$dateFrom, $dateTo])
			->groupBy('date_point')
			->orderBy('date_point')
			->get();
	}
}
