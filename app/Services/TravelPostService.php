<?php

namespace App\Services;

use App\Http\Requests\StoreTravelPostRequest;
use App\Http\Requests\UpdateTravelPostRequest;
use App\Http\Resources\TravelPostResource;
use App\Models\TravelPost;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelPostService
{
    use ApiResponse;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function filterPosts(Request $request)
    {
        //colonne ammesse per l'ordinamento
        $allowed_columns = ['created_at', 'count_likes', 'count_comments'];
        //ordinamento consentito
        $allowed_orders = ['asc', 'desc'];

        //mi prendo i valori di order e orderby
        $column = in_array($request->query('orderBy'), $allowed_columns)
            ? $request->query('orderBy')
            : 'id';
        $order = in_array(strtolower($request->query('order')), $allowed_orders)
            ? $request->query('order')
            : 'asc';

        //mi prendo i valori di perPage e page
        $perPage = $request->query('perPage');
        $page = $request->query('page');

        $query = TravelPost::query()
            /* Carica il numero di likes e commenti */
            ->withCount(['likes', 'comments'])
            /* Filtro per nome */
            ->when($request->query('title'), function ($query, $name) {
                return $query->where('name', 'ILIKE', '%' . $name . '%');
            })
            /* Filtro per destinazione */
            ->when($request->query('country'), function ($query, $country) {
                return $query->where('country', $country);
            })
            /* Ordina */
            ->when($column, function ($query) use ($column, $order) {
                return $query->orderBy($column, $order);
            });

        /* Return condizionale */
        return $perPage || $page
            ? //se l'utente passa perPage vuol dire che è interessato alla paginazione
            $query->paginate($perPage ?? 12, ['*'], $page ?? 1)->withQueryString()
            : //altrimenti mostro tutti i prodotti
            $query->get();
    }

    public function index()
    {
        return $this->apiResponse(true, TravelPostResource::collection(TravelPost::all()));
    }

    public function store(StoreTravelPostRequest $request)
    {
        $data = $request->validated();

        $post = Auth::user()->post()->create($data);

        return $this->apiResponse(true, new TravelPostResource($post), 201);
    }

    public function show(TravelPost $travel_post)
    {
        return $this->apiResponse(true, new TravelPostResource($travel_post));
    }

    public function update(UpdateTravelPostRequest $request, TravelPost $travel_post)
    {
        $data = $request->validate();

        return $this->apiResponse(true, new TravelPostResource($travel_post->update($data)));
    }

    public function destroy(TravelPost $travel_post)
    {
        $travel_post->delete();

        return $this->apiResponse(true, null, 204);
    }
}
