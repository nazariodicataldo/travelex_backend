<?php

namespace App\Services;

use App\Http\Requests\StoreLikeRequest;
use App\Models\Like;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class LikeService
{
    use ApiResponse;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store(StoreLikeRequest $request)
    {
        //valido i dati
        $data = $request->validated();

        $user_id = Auth::user()->id;

        //prima rimuovo a prescindere il record
        $deleted = Like::where('user_id', $user_id) //mi prendo l'id dell'utente autenticato
            ->where('travel_post_id', $data['travel_post_id']) // mi prendo l'id del post dalla richiesta validata
            ->delete(); //rimuovo il recordo

        //Se non è stato eliminato nessun record, farò un inserimento da 0
        if (!$deleted) {
            Like::create([
                'user_id' => $user_id,
                'travel_post_id' => $data['travel_post_id'],
            ]);
        }

        return $this->apiResponse(true, null);
    }
}
