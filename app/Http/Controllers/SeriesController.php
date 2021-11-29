<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Serie;
use App\Models\Temporada;
use App\Models\Episodio;
use Illuminate\Http\Response;

class SeriesController extends Controller
{
    /**
     * Lista todas as series cadastradas no SGBD
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $series = Serie::all();
        for ($i = 0; $i < count($series); $i++) {
            $series[$i]->temporadas = Temporada::where('serie_id', '=', $series[$i]->id)->orderBy('numero')->get();
            $qtdeTemporadas = count($series[$i]->temporadas);
            if ($qtdeTemporadas > 0) {
                for ($y = 0; $y < $qtdeTemporadas; $y++) {
                    $series[$i]->temporadas[$y]->episodios = Episodio::where('temporada_id', '=', $series[$i]->temporadas[$y]->id)->orderBy('numero')->get();
                }
            }
        }
        return response($series, 200);
    }

    /**
     * Cria um novo registro de série no SGBD
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $request->validate(['nome' => 'required|min:4']);
        $request->last_episode_watched = "S0E0";
        error_log($request);
        $serieCadastrada = Serie::create($request->all());
        return response($serieCadastrada, 201);
    }

    /**
     * Exibe os dados de um série específica
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

        $serie = Serie::findOrFail($id);
        return response($serie, 200);
    }

    /**
     * Atualiza uma série específica
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
            'nome' => 'min:5|string',
            'status' => 'in:assistido,não-assistido'
        ]);

        $serie = Serie::find($id);

        if ($serie == null) {
            return response('No content', 204);
        }

        if (isset($request['nome'])) {
            $serie->nome = $request['nome'];
        }

        if (isset($request['status'])) {
            $serie->status = $request['status'];
        }

        if (isset($request['categoria'])) {
            $serie->categoria = $request['categoria'];
        }

        if (isset($request['streaming'])) {
            $serie->streaming = $request['streaming'];
        }

        $serie->save();

        return response($serie, 200);
    }

    /**
     * Atualiza o status da série no SGBD
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id): Response
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            return response('Not found', 404);
        }

        $serie = Serie::find($id);

        if ($serie == null) {
            return response('No content', 204);
        }

        if ($serie->status === 'não-assistido') {
            $serie->status = 'assistido';
        } else {
            $serie->status = 'não-assistido';
        }
        $serie->save();

        return response($serie, 200);
    }

    /**
     * Exclui uma série do SGBD
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

        $serie = Serie::find($id);

        if ($serie == null) {
            return response('No content', 204);
        }

        Serie::destroy($id);

        return response('Ok', 200);
    }
}
