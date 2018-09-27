<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Shortlink;
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
		return Shortlink::select('id', 'url', 'hash', 'clicks')
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
			return response()->json($validator->errors()->first('url'), 400);
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
		$shortlink = Shortlink::select('id', 'url', 'hash', 'clicks')
			->where('user_id', \Auth::user()->id)
			->where('id', $id)
			->first();

		if (!is_null($shortlink)){
			return $shortlink;
		} else {
			return response()->json('Link not found.', 400);
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
			return response()->json('Link not found.', 400);
		}
    }
    
    public function click($hash)
	{
		$shortlink = Shortlink::where('hash', $hash)
			->first();

		if (!is_null($shortlink)){
			$shortlink->clicks = $shortlink->clicks + 1;
			$shortlink->save();
			return \Redirect::to('//' . $shortlink->url);
		} else {
			return \Redirect::to('/');
		}
	}
}
