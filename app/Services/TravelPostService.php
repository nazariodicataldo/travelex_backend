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
        $authId = auth('sanctum')->id();

        //colonne ammesse per l'ordinamento
        $allowed_columns = ['created_at', 'likes_count', 'comments_count'];
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
            /* Carica i likes e verifica se esiste un utente con lo stesso Id dell'utente autenticato e ritorna 1/0 */
            ->withExists([
                'likes as liked_by_me' => function ($q) use ($authId) {
                    $q->where('user_id', $authId);
                },
            ])
            /* Carica i commenti e li ordina */
            ->with([
                'comments' => function ($query) {
                    //carico i commenti, ordinandoli per ultimi pubblicati
                    $query->orderBy('created_at', 'desc');
                },
            ])
            /* Filtro per location */
            ->when($request->query('location'), function ($query, $location) {
                return $query->where('location', 'ILIKE', '%' . $location . '%');
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
            $query->simplePaginate($perPage ?? 12, ['*'], 'page', $page ?? 1)->withQueryString()
            : //altrimenti mostro tutti i prodotti
            $query->get();
    }

    public function index(Request $request)
    {
        return $this->apiResponse(
            true,
            TravelPostResource::collection($this->filterPosts($request)),
        );

        /* $posts = $this->filterPosts($request);

        // Genera l'intervallo di tutti i link (da pagina 1 a lastPage)
        $allLinks = collect($posts->getUrlRange(1, $posts->lastPage()));

        return $this->apiResponse(
            true,
            TravelPostResource::collection($this->filterPosts($request)),
            200,
            null,
            [
                'total' => $posts->total(),
                'currentPage' => $posts->currentPage(),
                'lastPage' => $posts->lastPage(),
                'perPage' => $posts->perPage(),
                'next' => $posts->nextPageUrl(),
                'prev' => $posts->previousPageUrl(),
                'allPages' => $allLinks,
            ],
        ); */
    }

    public function store(StoreTravelPostRequest $request)
    {
        $data = $request->validated();

        $post = Auth::user()->travelPosts()->create($data);

        return $this->apiResponse(true, new TravelPostResource($post), 201);
    }

    public function show(string $id)
    {
        $authId = auth('sanctum')->id();

        $travel_post = TravelPost::whereId($id)
            ->withCount(['likes', 'comments'])
            ->withExists([
                'likes as liked_by_me' => function ($q) use ($authId) {
                    $q->where('user_id', $authId);
                },
            ])
            ->with([
                'comments' => function ($query) {
                    //carico i commenti, ordinandoli per ultimi pubblicati
                    $query->orderBy('created_at', 'desc');
                },
            ])

            ->first();
        return $this->apiResponse(true, new TravelPostResource($travel_post));
    }

    public function update(UpdateTravelPostRequest $request, TravelPost $travel_post)
    {

        $data = $request->validated();

        $travel_post->update($data);

        return $this->apiResponse(true, new TravelPostResource($travel_post->refresh()));
    }

    public function destroy(TravelPost $travel_post)
    {
        $travel_post->delete();

        return $this->apiResponse(true, 'Post deleted successfully', 200);
    }
}
