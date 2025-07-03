<?php

namespace GarAppa\Http\Controllers;

use Illuminate\Http\Request;
use GarAppa\State;
use GarAppa\City;

class LocationController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return json
     */
    public function index()
    {
    	return response()->json(['return' => 'Retorno']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return json
     */
    public function list($state_id)
    {
     	$state = State::where('abbreviation', $state_id)->first();

     	if($state) {
     		$cities = City::where('state_id', $state->id)->GET();
     	}

    	return response()->json($cities);
    }
}
