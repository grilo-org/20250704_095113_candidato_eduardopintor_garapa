<?php

namespace GarAppa\Http\Controllers;

use GarAppa\Food;
use GarAppa\Establishment;
use Illuminate\Http\Request;
use GarAppa\Http\Requests\FoodRequest;

class FoodsController extends Controller
{

    /**
     * @var Factory
     */
    private $title;

    public function __construct()
    {
        $this->middleware('auth');

        $this->title = 'Comidas';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $foods = Food::all();
        $title = $this->title;

//        echo '<pre>'. $request->user()->hasRole('admin').'</pre>';

        return view('admin.foods.index', compact('foods', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  'Nova Comida';
        $establishments = Establishment::all();

        return view('admin.foods.create', compact('title', 'establishments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FoodRequest $request)
    {
        $data = $request->all();
        unset($data['_token']);

        try {
            /**
             * Create a food
             */
            $food = new Food();
            $food->establishment_id = $data['establishment_id'];
            $food->food_name = $data['food_name'];
            $food->firebase_id = '';
            $food->save();

            \Session::flash('flash_message','Comida criada com sucesso.');

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return redirect()->route('comidas');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title =  'Editar Comida';

        $food = Food::find($id);
        $establishments = Establishment::all();

        /**
         * Return to foods, if $id is invalid
         */
        if(!$food) {
            \Session::flash('flash_message','Comida não encontrada.');
            \Session::flash('flash_message_type','danger');

            return redirect()->route('comidas');
        }

        return view('admin.foods.edit', compact('id', 'food', 'establishments', 'title' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(FoodRequest $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);

        try {

            $food = Food::find($id);

            /**
             * Return to foods, if $id is invalid
             */
            if(!$food) {
                \Session::flash('flash_message','Comida não encontrada.');
                \Session::flash('flash_message_type','danger');

                return redirect()->route('comidas');
            }

            $food->update($data);

            \Session::flash('flash_message','Comida atualizada com sucesso.');

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return redirect()->route('comidas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $food = Food::find($id);

            if($food) {
                $food->delete();
                \Session::flash('flash_message','Comida excluída com sucesso.');
            } else {
                \Session::flash('flash_message','Comida não encontrada.');
                \Session::flash('flash_message_type','danger');
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }

        return redirect()->route('comidas');
    }
}
