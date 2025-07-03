<?php

namespace GarAppa\Http\Controllers;

use GarAppa\Award;
use GarAppa\Category;
use GarAppa\Cuisine;
use GarAppa\Curator;
use GarAppa\Establishment;
use GarAppa\EstablishmentAward;
use GarAppa\EstablishmentCategory;
use GarAppa\EstablishmentCuisine;
use GarAppa\EstablishmentCurator;
use GarAppa\EstablishmentFood;
use GarAppa\EstablishmentOperation;
use GarAppa\EstablishmentPeculiarity;
use GarAppa\EstablishmentPhoto;
use GarAppa\EstablishmentSpecialEnvironment;
use GarAppa\EstablishmentType;
use GarAppa\Operation;
use GarAppa\Peculiarity;
use GarAppa\PriceRange;
use GarAppa\SpecialEnvironment;
use GarAppa\UserEstablishmentIndicator;
use GarAppa\State;
use GarAppa\City;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use GarAppa\Http\Requests\EstablishmentRequest;
use Illuminate\Support\Facades\Response;

class EstablishmentsController extends Controller
{

    private $title;
    private $filters;
    private $imagePath;

    public function __construct()
    {
        $this->middleware('auth');

        $this->title = 'Experiências';
        $this->imagePath = 'images/establishments';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $establishments = Establishment::all();

        $title = $this->title;
        $filters = $this->filters;

        if(\Auth::user()->hasRole('curator')){
            $curator_id = \Auth::user()->curator_id;

            $establishmentCuratorFromDb = EstablishmentCurator::where('curator_id', $curator_id)->get();

            $establishmentCurator = [];

            foreach ($establishmentCuratorFromDb as $item) {
                $establishmentCurator[] = $item->establishment_id;
            }

            if($establishmentCurator) {

                foreach ($establishments as $key => $establishment) {
                    $id = $establishment->id;

                    if(in_array($id, $establishmentCurator)) {

                        $establishments[$key]['category']   = $this->getEstablishmentCategory($id);
                        $establishments[$key]['reviews']  = $this->getReviewsTotals($establishment);
                        $establishments[$key]['wantToGo']   = $this->getWantToGo($id);
                    } else {
                        unset($establishments[$key]);
                    }
                }

            }

        }else{
            foreach ($establishments as $key => $establishment) {
                $id = $establishment->id;
                $establishments[$key]['category']   = $this->getEstablishmentCategory($id);
                $establishments[$key]['reviews']  = $this->getReviewsTotals($establishment);
                $establishments[$key]['wantToGo']   = $this->getWantToGo($id);
            }
        }

        return view('admin.establishments.index', compact('establishments', 'filters', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Nova Experiência';
        $states = State::all();
        $imagePath = $this->imagePath;

        $categories = Category::all();

        $filters = $this->getFilters();

        if(\Auth::user()->hasRole('curator')){
            $curators = [];
        }else{
            $curators = Curator::all();
        }

        return view('admin.establishments.create', compact('title', 'categories' ,'curators', 'filters', 'states', 'imagePath'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EstablishmentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EstablishmentRequest $request)
    {       
        $data = $request->all();
        $data['facebook_url'] =  'facebook.com/'. $data['facebook_url'];
        $data['instagram_url'] =  'instagram.com/'. $data['instagram_url'];
        $data['status'] = (isset($data['status'])) ? 1 : 0;

        $curators_id = (isset($data['curatorId'])) ? $data['curatorId'] : [];

        unset($data['_token']);
        unset($data['curatorId']);

        if(isset($data['address'])) {
            foreach ($data['address'] as $key => $value) {
                $data[$key] = $value;
            }

            unset($data['address']);
        }

        if(isset($data['geo'])) {
            $data['latitude'] = floatval($data['geo']['latitude']);
            $data['longitude'] = floatval($data['geo']['longitude']);

            unset($data['geo']);
        }

        $data['firebase_id'] = '';

        if(isset($data['priceRanges']))
        {
            $data['price_range_id'] = $data['priceRanges'][0];

        }

        try {

            $establishment = Establishment::create($data);

            /**
             * Store image and save filename
             */
            if($data['image'])
            {
                $image = $data['image'];

                /**
                 * Store the new image
                 */
                $filename = $image->store($this->imagePath, 'public');
                $filename = str_replace($this->imagePath . '/', '', $filename);

                EstablishmentPhoto::create([
                    'filename'  => $filename,
                    'establishment_id'  => $establishment->id
                ]);
            }

            /**
             * Create record in relationships
             */
            // AWARDS
            if(!empty($data['awards'])){
                foreach ($data['awards'] as $award_id) {
                    EstablishmentAward::create([
                        'award_id'    => $award_id,
                        'establishment_id'  => $establishment->id
                    ]);
                }
            }
            // CATEGORY
            EstablishmentCategory::create([
                'establishment_id'  => $establishment->id,
                'category_id'  => $data['category_id']
            ]);
            // CUISINES
            if(!empty($data['cuisines'])){
                foreach ($data['cuisines'] as $cuisine_id) {
                    EstablishmentCuisine::create([
                        'cuisine_id'    => $cuisine_id,
                        'establishment_id'  => $establishment->id
                    ]);
                }
            }
            // CURATORS
            if(!empty($curators_id)){
                foreach ($curators_id as $curator_id) {
                    $establishmentCurator = EstablishmentCurator::where('curator_id', $curator_id)->where('establishment_id', $establishment->id)->first();
                    if(!$establishmentCurator){
                        EstablishmentCurator::create([
                            'curator_id'    => $curator_id,
                            'establishment_id'  => $establishment->id
                        ]);
                    }
                }
            }
            // OPERATIONS
            if(!empty($data['operations'])){
                foreach ($data['operations'] as $operation_id) {
                    EstablishmentOperation::create([
                        'operation_id'    => $operation_id,
                        'establishment_id'  => $establishment->id
                    ]);
                }
            }
            // PECULIARITIES
            if(!empty($data['peculiarities'])){
                foreach ($data['peculiarities'] as $peculiarity_id) {
                    EstablishmentPeculiarity::create([
                        'peculiarity_id'    => $peculiarity_id,
                        'establishment_id'  => $establishment->id
                    ]);
                }
            }
            // SPECIAL ENVIRONMENT
            if(!empty($data['specialEnvironments'])){
                foreach ($data['specialEnvironments'] as $specialEnvironment_id) {
                    EstablishmentSpecialEnvironment::create([
                        'special_environment_id'    => $specialEnvironment_id,
                        'establishment_id'  => $establishment->id
                    ]);
                }
            }
            // TYPES
            if(!empty($data['types'])){
                foreach ($data['types'] as $type_id) {
                    EstablishmentType::create([
                        'type_id'    => $type_id,
                        'establishment_id'  => $establishment->id
                    ]);
                }
            }

            \Session::flash('flash_message','Experiência criada com sucesso.');

        } catch (ApiException $e) {
            $apiRequest = $e->getRequest();
            $response = $e->getResponse();

            echo $apiRequest->getUri().PHP_EOL;
            echo $apiRequest->getBody().PHP_EOL;

            if ($response) {
                echo $response->getBody();
            }
        }

        return redirect()->route('experiencias');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Editar Experiência';
        $establishment = Establishment::find($id);
        $establishment->status = $establishment->status ?? 0;

        $establishment->category_id = EstablishmentCategory::where('establishment_id', $id)->get()->first()->category_id;

        $establishment->facebook_url = (isset($establishment->facebook_url)) ? str_replace('www.', '', $establishment->facebook_url) : '';
        $establishment->facebook_url = ($establishment->facebook_url != '') ? str_replace('facebook.com/', '', $establishment->facebook_url) : '';
        $establishment->instagram_url = (isset($establishment->instagram_url)) ? str_replace('instagram.com/', '', $establishment->instagram_url) : '';

        $states = State::all();
        $cities = City::all();
        $imagePath = $this->imagePath;

        // Filters
        $filters = $this->getFilters();

        // Filters selecteds
        $filtersSelecteds = $this->getEstablishmentFiltersSelecteds($id);

        // Categories
        $categories = Category::all();

        if(\Auth::user()->hasRole('curator')){
            $curators = [];
        }else{
            $curators = Curator::all();
            $establishmentCuratorsAll = EstablishmentCurator::where('establishment_id', $id)->get();

            if($establishmentCuratorsAll){
                foreach ($establishmentCuratorsAll as $establishmentCurator) {
                    $establishmentCurators[] = $establishmentCurator->curator_id;
                }
            }
        }

        //Evaluates
        $establishment['reviews'] = $this->getReviewsTotals($establishment);
        $establishment['wantToGo'] = $this->getWantToGo($id);

        return view('admin.establishments.edit',
            compact('id', 'establishment', 'establishmentCurators', 'curators', 'categories', 'filters', 'filtersSelecteds', 'title', 'states', 'cities', 'imagePath'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EstablishmentRequest $request, $id)
    {
        $data = $request->all();
        $data['facebook_url'] =  'facebook.com/'. $data['facebook_url'];
        $data['instagram_url'] =  'instagram.com/'. $data['instagram_url'];
        $data['status'] = (isset($data['status'])) ? 1 : 0;

        $curators_id = (isset($data['curatorId'])) ? $data['curatorId'] : [];

        unset($data['_token']);
        unset($data['curatorId']);

        if(isset($data['address'])) {
            foreach ($data['address'] as $key => $value) {
                $data[$key] = $value;
            }

            unset($data['address']);
        }

        if(isset($data['geo'])) {
            $data['latitude'] = floatval($data['geo']['latitude']);
            $data['longitude'] = floatval($data['geo']['longitude']);

            unset($data['geo']);
        }

        $data['firebase_id'] = '';

        if(isset($data['priceRanges']))
        {
            $data['price_range_id'] = $data['priceRanges'][0];

        }

        try {
            /**
             * Get Establishment
             * and update it
             */
            $establishment = Establishment::find($id);
            $establishment->update($data);

            /**
             * Store image and save filename
             */
            if(isset($data['image']) && ($data['image'] != $establishment->photo->filename))
            {
                $image = $data['image'];
                $establishmentPhoto = EstablishmentPhoto::where([
                    'establishment_id'  => $establishment->id
                ])->get()->first();

                /**
                 * Store the new image
                 */
                $filename = $image->store($this->imagePath, 'public');
                $filename = str_replace($this->imagePath . '/', '', $filename);

                /**
                 * Delete old image
                 */
                Storage::disk('local')->delete('public/' . $this->imagePath . '/' .$establishmentPhoto->filename);

                $establishmentPhoto->update(['filename'  => $filename]);
            }

            /**
             * Update record in relationships
             */
            // AWARDS
            if(!empty($data['awards'])){
                $awards = EstablishmentAward::where('establishment_id', $id)->get();
                $establishmentAwards = [];

                foreach ($awards as $award){
                    $establishmentAwards[] = $award->award_id;
                }

                /**
                 * Create record if not exists
                 */
                foreach ($data['awards'] as $award_id) {
                    if(!in_array($award_id, $establishmentAwards)){
                        EstablishmentAward::create([
                            'award_id'    => $award_id,
                            'establishment_id'  => $establishment->id
                        ]);
                    }
                }
            } else {
                EstablishmentAward::where('establishment_id', $id)->delete();
            }

            // CATEGORY
            EstablishmentCategory::where(['establishment_id'  => $id])
                ->update(['category_id'  => $data['category_id']]);

            // CUISINES
            if(!empty($data['cuisines'])){
                $cuisines = EstablishmentCuisine::where('establishment_id', $id)->get();
                $establishmentCuisines = [];

                foreach ($cuisines as $cuisine){
                    $establishmentCuisines[] = $cuisine->cuisine_id;
                }

                /**
                 * Create record if not exists
                 */
                foreach ($data['cuisines'] as $cuisine_id) {
                    if(!in_array($cuisine_id, $establishmentCuisines)){
                        EstablishmentCuisine::create([
                            'cuisine_id'    => $cuisine_id,
                            'establishment_id'  => $establishment->id
                        ]);
                    }
                }
            } else {
                EstablishmentCuisine::where('establishment_id', $id)->delete();
            }
            // CURATORS
            if(!empty($curators_id)){
                $curators = EstablishmentCurator::where('establishment_id', $id)->get();
                $establishmentCurators = [];

                foreach ($curators as $curator){
                    $establishmentCurators[] = $curator->curator_id;
                }

                /**
                 * Create record if not exists
                 */
                foreach ($curators_id as $curator_id) {
                    if(!in_array($curator_id, $establishmentCurators)){
                        EstablishmentCurator::create([
                            'curator_id'    => $curator_id,
                            'establishment_id'  => $establishment->id
                        ]);
                    }
                }
            } else {
                EstablishmentCurator::where('establishment_id', $id)->delete();
            }
            // OPERATIONS
            if(!empty($data['operations'])){
                $operations = EstablishmentOperation::where('establishment_id', $id)->get();
                $establishmentOperations = [];

                foreach ($operations as $operation){
                    $establishmentOperations[] = $operation->operation_id;
                }

                /**
                 * Create record if not exists
                 */
                foreach ($data['operations'] as $operation_id) {
                    if(!in_array($operation_id, $establishmentOperations)){
                        EstablishmentOperation::create([
                            'operation_id'    => $operation_id,
                            'establishment_id'  => $establishment->id
                        ]);
                    }
                }
            } else {
                EstablishmentOperation::where('establishment_id', $id)->delete();
            }
            // PECULIARITIES
            if(!empty($data['peculiarities'])){
                $peculiarities = EstablishmentPeculiarity::where('establishment_id', $id)->get();
                $establishmentPeculiarities = [];

                foreach ($peculiarities as $peculiarity){
                    $establishmentPeculiarities[] = $peculiarity->peculiarity_id;
                }

                /**
                 * Create record if not exists
                 */
                foreach ($data['peculiarities'] as $peculiarity_id) {
                    if(!in_array($peculiarity_id, $establishmentPeculiarities)){
                        EstablishmentPeculiarity::create([
                            'peculiarity_id'    => $peculiarity_id,
                            'establishment_id'  => $establishment->id
                        ]);
                    }
                }
            } else {
                EstablishmentPeculiarity::where('establishment_id', $id)->delete();
            }

            // SPECIAL ENVIRONMENT
            if(!empty($data['specialEnvironments'])){
                $specialEnvironments = EstablishmentSpecialEnvironment::where('establishment_id', $id)->get();
                $establishmentSpecialEnvironments = [];

                foreach ($specialEnvironments as $specialEnvironment){
                    $establishmentSpecialEnvironments[] = $specialEnvironment->special_environment_id;
                }

                /**
                 * Create record if not exists
                 */
                foreach ($data['specialEnvironments'] as $special_environment_id) {
                    if(!in_array($special_environment_id, $establishmentSpecialEnvironments)){
                        EstablishmentSpecialEnvironment::create([
                            'special_environment_id'    => $special_environment_id,
                            'establishment_id'  => $establishment->id
                        ]);
                    }
                }
            } else {
                EstablishmentSpecialEnvironment::where('establishment_id', $id)->delete();
            }

            // TYPES
            if(!empty($data['types'])){
                foreach ($data['types'] as $type_id) {
                    EstablishmentType::create([
                        'type_id'    => $type_id,
                        'establishment_id'  => $establishment->id
                    ]);
                }
            }

            \Session::flash('flash_message','Experiência atualizada com sucesso.');

        } catch (ApiException $e) {
            $apiRequest = $e->getRequest();
            $response = $e->getResponse();

            echo $apiRequest->getUri().PHP_EOL;
            echo $apiRequest->getBody().PHP_EOL;

            if ($response) {
                echo $response->getBody();
            }
        }

        return redirect()->route('experiencias');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            /**
             * Get the establishment
             */
            $establishment = Establishment::find($id);

            /**
             * Returns to listing with error if establishment is not found
             */
            if(!$establishment)
            {
                \Session::flash('flash_message_type','danger');
                \Session::flash('flash_message','Experiência não encontrada.');

                return redirect()->route('experiencias');
            }

            /**
             * Delete all records from this establishment in relationships tables
             */
            EstablishmentAward::where('establishment_id', $id)->delete();
            EstablishmentCategory::where('establishment_id', $id)->delete();
            EstablishmentCuisine::where('establishment_id', $id)->delete();
            EstablishmentCurator::where('establishment_id', $id)->delete();
            EstablishmentFood::where('establishment_id', $id)->delete();
            EstablishmentOperation::where('establishment_id', $id)->delete();
            EstablishmentPeculiarity::where('establishment_id', $id)->delete();
            EstablishmentSpecialEnvironment::where('establishment_id', $id)->delete();
            EstablishmentType::where('establishment_id', $id)->delete();

            $establishmentPhoto = EstablishmentPhoto::where([
                'establishment_id'  => $id
            ])->get()->first();

            /**
             * Delete the image
             */
            Storage::disk('local')->delete('public/' . $this->imagePath . '/' .$establishmentPhoto->filename);
            $establishmentPhoto->delete();

            /**
             * Delete the establishment
             */
            $establishment->delete();

            \Session::flash('flash_message','Experiência excluída com sucesso.');

        } catch (ApiException $e) {
            $apiRequest = $e->getRequest();
            $response = $e->getResponse();

            echo $apiRequest->getUri().PHP_EOL;
            echo $apiRequest->getBody().PHP_EOL;

            if ($response) {
                echo $response->getBody();
            }
        }

        return redirect()->route('experiencias');
    }

    /**
     * Return the category name of an establishment
     * @param $establishment_id
     * @return string
     */
    private function getEstablishmentCategory($establishment_id)
    {
        $establishment_category = EstablishmentCategory::where('establishment_id', $establishment_id)->first();

        if(!$establishment_category){
            return '';
        }

        $category = Category::where('id', $establishment_category->category_id)->get()->first();

        return $category->category_name;

    }

    /**
     * Get all filters
     *
     * @return array
     */
    public function getFilters()
    {
        $categories = [
            'awards'    => [ 'name'  => 'Prêmios', 'data'  => [] ],
            'cuisines'    => [ 'name'  => 'Cozinhas', 'data'  => [] ],
            'operations'    => [ 'name'  => 'Operações', 'data'  => [] ],
            'peculiarities'    => [ 'name'  => 'Peculiaridades', 'data'  => [] ],
            'priceRanges'    => [ 'name'  => 'Faixas de preços', 'data'  => [] ],
            'specialEnvironments'    => [ 'name'  => 'Ambientes especiais', 'data'  => [] ],
        ];

        $awards         = Award::all();
        $cuisines        = Cuisine::all();
        $operations      = Operation::all();
        $peculiarities    = Peculiarity::all();
        $priceRanges     = PriceRange::all();
        $specialEnvironments = SpecialEnvironment::all();

        foreach ($awards as $award) {
            $categories['awards']['data'][] = [
                'id'    => $award->id,
                'name'    => $award->award_name
            ];
        }
        foreach ($cuisines as $cuisine) {
            $categories['cuisines']['data'][] = [
                'id'    => $cuisine->id,
                'name'    => $cuisine->cuisine_name
            ];
        }
        foreach ($operations as $operation) {
            $categories['operations']['data'][] = [
                'id'    => $operation->id,
                'name'    => $operation->operation_period
            ];
        }
        foreach ($peculiarities as $peculiarity) {
            $categories['peculiarities']['data'][] = [
                'id'    => $peculiarity->id,
                'name'    => $peculiarity->peculiarity_description
            ];
        }
        foreach ($priceRanges as $priceRange) {
            $categories['priceRanges']['data'][] = [
                'id'    => $priceRange->id,
                'name'    => $priceRange->price_range_description
            ];
        }
        foreach ($specialEnvironments as $specialEnvironment) {
            $categories['specialEnvironments']['data'][] = [
                'id'    => $specialEnvironment->id,
                'name'    => $specialEnvironment->special_environment_description
            ];
        }

        return $categories;
    }

    /**
     * Get establishment filters selecteds
     *
     * @param int $establishment_id
     * @return array
     */
    public function getEstablishmentFiltersSelecteds(int $establishment_id)
    {
        $filtersSelecteds = [
            'awards'    => [],
            'cuisines'  => [],
            'operations'    => [],
            'peculiarities' => [],
            'priceRanges'   => [],
            'specialEnvironments'   => [],
        ];

        $awards         = EstablishmentAward::where('establishment_id', $establishment_id)->get();
        $cuisines        = EstablishmentCuisine::where('establishment_id', $establishment_id)->get();
        $operations      = EstablishmentOperation::where('establishment_id', $establishment_id)->get();
        $peculiarities    = EstablishmentPeculiarity::where('establishment_id', $establishment_id)->get();
        $priceRanges     = Establishment::find($establishment_id)->price_range_id;
        $specialEnvironments = EstablishmentSpecialEnvironment::where('establishment_id', $establishment_id)->get();

        foreach ($awards as $award) {
            $filtersSelecteds['awards'][] = $award->award_id;
        }
        foreach ($cuisines as $cuisine) {
            $filtersSelecteds['cuisines'][] = $cuisine->cuisine_id;
        }
        foreach ($operations as $operation) {
            $filtersSelecteds['operations'][] = $operation->operation_id;
        }
        foreach ($peculiarities as $peculiarity) {
            $filtersSelecteds['peculiarities'][] = $peculiarity->peculiarity_id;
        }

        $filtersSelecteds['priceRanges'][0] = $priceRanges;

        foreach ($specialEnvironments as $specialEnvironment) {
            $filtersSelecteds['specialEnvironments'][] = $specialEnvironment->special_environment_id;
        }

        return $filtersSelecteds;
    }

    /**
     * Returns totals of reviews in Establishment
     *
     * @param Establishment $establishment
     * @return array
     */
    public function getReviewsTotals(Establishment $establishment)
    {
        $reviewData = [
            'total' => count($establishment->reviews),
            'wouldComeBack' => 0,
            'wouldNotComeBack' => 0,
        ];

        foreach ($establishment->reviews as $review) {
            if ($review->would_come_back) {
                $reviewData['wouldComeBack']++;
            } else {
                $reviewData['wouldNotComeBack']++;
            }

        }
        return $reviewData;
    }

    public function getWantToGo($establishmentId)
    {
        $totalWantToGo = UserEstablishmentIndicator::where('want_to_go', 1)->where('establishment_id', $establishmentId)->get()->count();

        return $totalWantToGo;
    }

    /**
     * Export excel
     *
     * @return excel file
     */
    public function export()
    {
        /**
         * Get establishments and set filename
         */
        $establishments = Establishment::all();
        $filename = 'garappa_'. strtolower($this->title) .'_'. date('Ymd_His');

        /**
         * Get all filters
         */
        $filters = $this->getFilters();

        /**
         * Set file headers and default columns
         */
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
            'Endereço',
            'Número',
            'Bairro',
            'Cidade',
            'Estado',
            'CEP',
            'Telefone',
            'Facebook',
            'Instagram',
            'Latitude',
            'Longitude',
            'Status',
            'Categorias',
            'Avaliações',
            'Voltaria',
            'Não voltaria',
            'Quer ir',
        ];

        /**
         * Add filters to columns
         */
        foreach ($filters as $filter) {
            $columns[] = $filter['name'];
        }

        $rows = [];

        foreach ($establishments as $establishment) {
            $establishmentCategory = EstablishmentCategory::where('establishment_id', $establishment->id)->get()->first();
            $category_name = '';

            if($establishmentCategory) {
                $category_name = Category::find($establishmentCategory->category_id)->category_name;
            }

            /**
             * Insert the establishment data on line
             */
            $line = [
                $establishment->name ?? '',
                $establishment->street ?? '',
                $establishment->number ?? '',
                $establishment->neighborhood ?? '',
                $establishment->city ?? '',
                $establishment->state ?? '',
                $establishment->zipcode ?? '',
                $establishment->phone ?? '',
                $establishment->facebook_url ?? '',
                $establishment->instagram_url ?? '',
                $establishment->latitude ?? '',
                $establishment->longitude ?? '',
                (!empty($establishment['status'])) ? 'Ativo': 'Inativo',
                $category_name,
            ];

            /**
             * Insert reviews totals
             */
            $reviews = $this->getReviewsTotals($establishment);
            $line[] = $reviews['total'];
            $line[] = $reviews['wouldComeBack'];
            $line[] = $reviews['wouldNotComeBack'];
            $line[] = $this->getWantToGo($establishment->id);

            /**
             * Insert all establishment filters
             */
            $awards = EstablishmentAward::where('establishment_id', $establishment->id)->get();
            if($awards->count() > 0){
                $itemsArray = [];
                foreach ($awards as $item) {
                    $item_name = Award::find($item->award_id)->award_name;
                    $itemsArray[] = $item_name;
                }
                $line[] = implode(', ', $itemsArray);
            } else {
                $line[] = '-';
            }

            $cuisines = EstablishmentCuisine::where('establishment_id', $establishment->id)->get();
            if($cuisines->count() > 0){
                $itemsArray = [];
                foreach ($cuisines as $item) {
                    $item_name = Cuisine::find($item->cuisine_id)->cuisine_name;
                    $itemsArray[] = $item_name;
                }
                $line[] = implode(', ', $itemsArray);
            } else {
                $line[] = '-';
            }

            $operations = EstablishmentOperation::where('establishment_id', $establishment->id)->get();
            if($operations->count() > 0){
                $itemsArray = [];
                foreach ($operations as $item) {
                    $item_name = Operation::find($item->operation_id)->operation_period;
                    $itemsArray[] = $item_name;
                }
                $line[] = implode(', ', $itemsArray);
            } else {
                $line[] = '-';
            }

            $peculiarities = EstablishmentPeculiarity::where('establishment_id', $establishment->id)->get();
            if($peculiarities->count() > 0){
                $itemsArray = [];
                foreach ($peculiarities as $item) {
                    $item_name = Peculiarity::find($item->peculiarity_id)->peculiarity_description;
                    $itemsArray[] = $item_name;
                }
                $line[] = implode(', ', $itemsArray);
            } else {
                $line[] = '-';
            }

            $priceRange = PriceRange::find($establishment->price_range_id);
            if($priceRange){
                $line[] = $priceRange->price_range_description;
            } else {
                $line[] = '-';
            }

            $specialEnvironments = EstablishmentSpecialEnvironment::where('establishment_id', $establishment->id)->get();
            if($specialEnvironments->count() > 0){
                $itemsArray = [];
                foreach ($specialEnvironments as $item) {
                    $item_name = SpecialEnvironment::find($item->special_environment_id)->special_environment_description;
                    $itemsArray[] = $item_name;
                }
                $line[] = implode(', ', $itemsArray);
            } else {
                $line[] = '-';
            }

            $rows[] = $line;
        }

        /**
         * Open the file and write data
         */
        $callback = function() use ($rows, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($rows as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };        

        // return response()->download($filename, $filename.'.csv', $headers);
        return response()->stream($callback, 200, $headers);
    }
}
