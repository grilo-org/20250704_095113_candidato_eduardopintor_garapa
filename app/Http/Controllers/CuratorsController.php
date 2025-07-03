<?php

namespace GarAppa\Http\Controllers;

use GarAppa\CuratorFood;
use GarAppa\EstablishmentCurator;
use GarAppa\Food;
use GarAppa\State;
use GarAppa\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use GarAppa\Http\Requests\CuratorRequest;
use Illuminate\Support\Facades\Response;
use GarAppa\User;
use GarAppa\Curator;
use GarAppa\Role;

class CuratorsController extends Controller
{

    private $title;
    private $imagePath;

    public function __construct()
    {
        $this->middleware('auth');

        $this->title = 'Curadores';
        $this->imagePath = 'images/curators';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Auth::user()->authorizeRoles(['admin', 'curator']);

        if(\Auth::user()->hasRole('curator')){
            return redirect()->route('curadores.edit', ['id'  =>  \Auth::user()->id]);
        }

        $curators = Curator::all();

        if($curators){
            foreach ($curators as $curator) {
                $user_curator = User::where('curator_id', $curator->id)->first();
                $curator['email'] = ($user_curator) ? $user_curator->email : '';
            }
        }

        $title = $this->title;
        $imagePath = $this->imagePath;

        return view('admin.curators.index', compact('curators', 'title', 'imagePath'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $title =  'Novo Curador';
        $states = State::all();

        $foods = Food::all();

        return view('admin.curators.create', compact('title', 'foods', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CuratorRequest $request)
    {
        $data = $request->all();
        $data['status'] = (isset($data['status'])) ? $data['status'] : 0;
        $foodCurator = (isset($data['foodCurator'])) ? $data['foodCurator'] : null;

        unset($data['foodCurator']);
        unset($data['_token']);

        if(!empty($accessData['password']) && empty($accessData['email'])) {
            $accessData =  [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password'])
            ];
        }

        unset($data['email']);
        unset($data['password']);

        foreach ($data as $key => $value) {
            $data[$key] = ($value != null) ? $value : '';
            if($key == 'instagram' && strpos($value, '@') === false) {
                $data[$key] = '@'.$value;
            }
        }

        try {

            /**
             * Create a record in curators table
             */
            $curator = new Curator();
            $curator->name = $data['name'];
            $curator->occupation = $data['occupation'];
            $curator->biography = $data['biography'];
            $curator->description = $data['description'];
            $curator->picture_url = '';
            $curator->firebase_id = '';
            $curator->city = $data['city'];
            $curator->state = $data['state'];
            $curator->facebook_url = $data['facebook_url'];
            $curator->instagram_url = $data['instagram_url'];
            $curator->site_url = $data['site_url'];
            $curator->save();

            /**
             * Store image and save filename
             */
            if($request->file('image'))
            {
                /**
                 * Store the new image
                 */
                $filename = $request->file('image')->store($this->imagePath, 'public');
                $filename = str_replace($this->imagePath . '/', '', $filename);
            }

            $curator->picture_url = $filename ?? $data['image'];
            $curator->save();

            if($foodCurator) {
                CuratorFood::create([
                    'curator_id'    => $curator->id,
                    'foods_ids' => $foodCurator
                ]);

            }

            if(isset($accessData)) {
                $user_curator = User::where('curator_id', $curator->id)->first();

                if(!$user_curator) {
                    // Create User acess
                    $role_curator = Role::where('name', 'curator')->first();
                    $user_curator = new User;
                    $user_curator->name = $accessData['name'];
                    $user_curator->email = $accessData['email'];
                    $user_curator->password = $accessData['password'];
                    $user_curator->curator_id = $curator->id;
                    $user_curator->save();
                    $user_curator->roles()->attach($role_curator);
                } else {
                    User::where('curator_id',$curator->id)->update($accessData);
                }
            }

            \Session::flash('flash_message','Curador criado com sucesso.');

        } catch (ApiException $e) {
            $apiRequest = $e->getRequest();
            $response = $e->getResponse();

            echo $apiRequest->getUri().PHP_EOL;
            echo $apiRequest->getBody().PHP_EOL;

            if ($response) {
                echo $response->getBody();
            }
        }

        return redirect()->route('curadores');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $title =  'Editar Curador';
        $imagePath = $this->imagePath;

        $curator = Curator::find($id);
        
        $user_curator = User::where('curator_id',$id)->first();
        $curator['email'] = ($user_curator) ? $user_curator ->email : '';

        $states = State::all();
        $cities = City::all();

        $foods = Food::all();

        $foodsCurator = CuratorFood::where('curator_id', $id)->first();
        $foodsCurator = ($foodsCurator) ? $foodsCurator->foods_ids : [];

        return view('admin.curators.edit', compact('id', 'curator', 'foods', 'foodsCurator', 'title', 'states', 'cities', 'imagePath'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CuratorRequest $request, $id)
    {

        $data = $request->all();
        $foods = (isset($data['foodCurator'])) ? $data['foodCurator'] : null;

        unset($data['foodCurator']);
        unset($data['_token']);


        if(!empty($accessData['password']) && empty($accessData['email'])) {
            $accessData =  [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ];
        }

        unset($data['email']);
        unset($data['password']);

        /**
         * Select Curator
         */
        $curator = Curator::find($id);

        foreach ($data as $key => $value) {
            $data[$key] = ($value != null) ? $value : '';
            if($key == 'instagram' && strpos($value, '@') === false) {
                $data[$key] = '@'.$value;
            }
        }

        try {
            /**
             * UPDATE CURATOR
             */
            $curator->update($data);

            if(isset($data['image']) && ($data['image'] != $curator->picture_url)){
                /**
                 * Update the image
                 */
                $filename = $data['image']->store($this->imagePath, 'public');
                $filename = str_replace($this->imagePath . '/', '', $filename);

                //Delete old image
                Storage::disk('local')->delete('public/' . $this->imagePath . '/' . $curator->picture_url);

                $curator->update(['picture_url'  => $filename]);
            }


            /**
             * UPDATE CURATOR FOODS
             */
            if($foods) {
                /**
                 * GET CURATOR FOODS OR CREATE IF NOT EXISTS
                 */
                $curatorFoods = CuratorFood::firstOrNew(['curator_id' => $id]);
                $curatorFoods->foods_ids = $foods;
                $curatorFoods->save();

            }

            if(isset($accessData)) {
                $user_curator = User::where('curator_id',$id)->first();
                if(!$user_curator) {
                // Create User acess
                    $role_curator = Role::where('name', 'curator')->first();
                    $user_curator = new User;
                    $user_curator->name = $accessData['name'];
                    $user_curator->email = $accessData['email'];
                    $user_curator->password = $accessData['password'];
                    $user_curator->curator_id = $id;
                    $user_curator->save();
                    $user_curator->roles()->attach($role_curator);              
                } else {
                    User::where('curator_id',$id)->update($accessData);
                }
            }

            \Session::flash('flash_message','Curador atualizado com sucesso.');

        } catch (ApiException $e) {
            $apiRequest = $e->getRequest();
            $response = $e->getResponse();

            echo $apiRequest->getUri().PHP_EOL;
            echo $apiRequest->getBody().PHP_EOL;

            if ($response) {
                echo $response->getBody();
            }
        }

        return redirect()->route('curadores');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $curator = Curator::find($id);
            $curatorData = $curator->toArray();

            if(isset($curatorData['picture_url'])) {
                Storage::disk('local')->delete('public/' . $this->imagePath . '/' . $curatorData['picture_url']);
            }

            /**
             * DELETE RELATIONSHIPS
             */
            CuratorFood::where('curator_id', $id)->delete();
            EstablishmentCurator::where('curator_id', $id)->delete();

            $user_curator = User::where('curator_id', $id)->first();
            if($user_curator){
                $user_curator->roles()->detach();
                $user_curator->delete();
            }

            /**
             * DELETE CURATOR
             */
            $curator->delete();

            \Session::flash('flash_message','Curador excluído com sucesso.');

        } catch (Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }

        return redirect()->route('curadores');
    }

    /**
     * Export excel
     *
     * @return excel file
     */
    public function export()
    {
        $curators = Curator::all();
        $states = (function_exists('list_states')) ? list_states() : [];
        $filename = 'garappa_'. strtolower($this->title) .'_'. date('Ymd_His');

        $headers = [
            'Cache-Control'=>'no-cache, no-store, max-age=0, must-revalidate',
            'Pragma'=>'no-cache',
            'Expires'=>'Fri, 01 Jan 1990 00:00:00 GMT',
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-type' => 'application/x-msexcel; charset=utf-8',
            'Content-disposition' => 'xls' . $filename . '.csv',
            'Content-Disposition' => 'attachment; filename="'. $filename .'.csv"'
        ];

        $columns = [
            'Nome',
            'Profissão',
            'Cidade',
            'UF',
            'Facebook',
            'Instagram',
            'Site',
            'Biografia',
            'Descrição',
        ];

        $callback = function() use ($curators, $columns) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);
            foreach ($curators as $curator) {
                $line = [
                    (!empty($curator['name'])) ? $curator['name'] : null,
                    (!empty($curator['occupation'])) ? $curator['occupation'] : null,
                    (!empty($curator['city'])) ? $curator['city'] : null,
                    (!empty($curator['state'])) ? $curator['state'] : null,
                    (!empty($curator['facebook_url'])) ? $curator['facebook_url'] : null,
                    (!empty($curator['instagram_url'])) ? $curator['instagram_url'] : null,
                    (!empty($curator['site_url'])) ? $curator['site_url'] : null,
                    (!empty($curator['biography'])) ? $curator['biography'] : null,
                    (!empty($curator['description'])) ? $curator['description'] : null,
                ];

                fputcsv($file, $line);
            }
            fclose($file);

        };

        return response()->stream($callback, 200, $headers);
    }
}
