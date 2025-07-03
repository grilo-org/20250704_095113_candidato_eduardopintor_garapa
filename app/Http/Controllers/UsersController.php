<?php

namespace GarAppa\Http\Controllers;

use GarAppa\UserApp;
use Illuminate\Http\Request;
use GarAppa\Http\Requests\CuratorRequest;
use Illuminate\Support\Facades\Response;

class UsersController extends Controller
{

    private $title;
    private $users;
    private $states;
    private $database;
    private $fields;

    public function __construct()
    {
        $this->middleware('auth');

        $this->title = 'Usuários';
        $this->fields = [
            'birthday',
            'city',
            'cpf',
            'creationDate',
            'email',
            'firstName',
            'fullName',
            'gender',
            'lastLoginDate',
            'lastName',
            'lastUpdate',
            'phoneNumber',
            'photoUrl',
            'state',
            'status',
            'termsOfUseAcceptDate',
            'username'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = $this->title;
        $users = UserApp::all();

        return view('admin.users.index', compact('users', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title =  'Dados do Usuário';
        $user = UserApp::find($id);
        $states = (function_exists('list_states')) ? list_states() : [];

        return view('admin.users.show', compact('id', 'user', 'title' , 'states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title =  $this->title .' - Novo';
        $states = (function_exists('list_states')) ? list_states() : [];

        return view('admin.users.create', compact('title', 'states'));
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
        $foods = $data['foodCurator'];
        $foodCurator = [];

        unset($data['foodCurator']);
        unset($data['_token']);


        foreach ($data as $key => $value) {
            $data[$key] = ($value != null) ? $value : '';
            if($key == 'instagram' && strpos($value, '@') === false) {
                $data[$key] = '@'.$value;
            }
        }

        try {

            $id = $this->users->push($data)->getKey();

            foreach ($foods as $key => $value) {
                $foodCurator[$id][] = $key;
            }

            $this->database->getReference('foods/foodCurator')
            ->update($foodCurator);

            \Session::flash('flash_message','Usuário criado com sucesso.');

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
        $title =  $this->title .' - Editar';
        $user = $this->users->getChild($id)
        ->getValue();
        $states = (function_exists('list_states')) ? list_states() : [];

        return view('admin.users.edit', compact('id', 'user', 'title' , 'states'));
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
        $foods = $data['foodCurator'];
        $foodCurator = [];

        unset($data['foodCurator']);
        unset($data['_token']);

        foreach ($foods as $key => $value) {
            $foodCurator[$id][] = $key;
        }

        foreach ($data as $key => $value) {
            $data[$key] = ($value != null) ? $value : '';
            if($key == 'instagram' && strpos($value, '@') === false) {
                $data[$key] = '@'.$value;
            }
        }

        try {
            $this->users->getChild($id)
            ->update($data);

            \Session::flash('flash_message','Usuário atualizado com sucesso.');

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
            $this->users->getChild($id)
            ->remove();

            \Session::flash('flash_message','Usuário excluído com sucesso.');

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
     * Export excel
     *
     * @return excel file
     */
    public function export()
    {
        $users = UserApp::all();
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
            'Nome Completo',
            'Primeiro Nome',
            'Sobrenome',
            'Email',
            'Telefone',
            'Nascimento',
            'Gênero',
            'CPF',
            'Cidade',
            'UF',
            'Username',
            'Status',
            'Termo de aceite',
            'Criado em',
            'Última atualização',
            'Ultimo login',
        ];

        $callback = function() use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                    $line = [
                        $user->full_name ?? '',
                        $user->first_name ?? '',
                        $user->last_name ?? '',
                        $user->email ?? '',
                        $user->phone_number ?? '',
                        $user->birthday ?? '',
                        (!empty($user->gender) ? ($user->gender == 'M' ? 'Masculino' : 'Feminino') : ''),
                        $user->cpf ?? '',
                        $user->city ?? '',
                        $user->state ?? '',
                        $user->username ?? '',
                        (!empty($user->status) ? ($user->status == 'a' ? 'Ativo' : 'Inativo') : ''),
                        $user->terms_of_use_accept_date ?? '',
                        $user->creation_date ?? '',
                        $user->last_update ?? '',
                        $user->last_login_date ?? '',
                    ];
                fputcsv($file, $line);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
