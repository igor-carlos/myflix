<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Episodio;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\DB;

class EpisodiosController extends Controller
{
    /**
     * Lista todos os episódios cadastradas no SGBD
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return response(Episodio::all(), 200);
    }

    /**
     * Cria um novo registro de episídio no SGBD
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $request->validate(['temporada_id' => 'required']);
        $request->validate(['numero' => 'required']);
        $request->validate(['nome' => 'required']);
        $episodio = Episodio::where('temporada_id', '=', $request['temporada_id'])->where('numero', '=', $request['numero'])->get();
        if (count($episodio) > 0) {
            return response("Episodio already exists", 409);
        }
        $episodioCadastrado = Episodio::create($request->all());
        return response($episodioCadastrado, 201);
    }

    /**
     * Exibe os dados de um episódio específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            return response("Not found", 404);
        }

        $ep = Episodio::findOrFail($id);
        return response($ep, 200);
    }

    /**
     * Atualiza um episódio específico
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): Response
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            return response("Not found", 404);
        }

        $request->validate([
            'nome' => 'string',
            'numero' => 'integer'
        ]);

        $ep = Episodio::find($id);

        if ($ep == null) {
            return response('No content', 204);
        }

        if (isset($request['nome'])) {
            $ep->nome = $request['nome'];
        }

        if (isset($request['numero'])) {
            $ep->numero = $request['numero'];
        }

        $ep->save();

        return response($ep, 200);
    }

    /**
     * Exclui um episódio do SGBD
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): Response
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            return response('Not found', 404);
        }

        $ep = Episodio::find($id);

        if ($ep == null) {
            return response('No content', 204);
        }

        Episodio::destroy($id);

        return response('Ok', 200);
    }
}
