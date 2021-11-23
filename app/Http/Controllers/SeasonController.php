<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Season;
use Illuminate\Http\Response;

class SeasonController extends Controller
{
  /**
   * Lista todas os registros encontrados
   *
   * @return \Illuminate\Http\Response
   */
  public function index(): Response
  {
    return response(Season::all(), 200);
  }

  /**
   * Cria um novo registro de temporada no SGBD
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request): Response
  {
    $request->validate(['serie_id' => 'required']);
    $request->validate(['episodeo' => 'required']);
    $request->validate(['temporada' => 'required']);
    $temporadaCadastrada = Season::create($request->all());
    return response($temporadaCadastrada, 201);
  }

  /**
   * Exibe as temporadas de uma série
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function showBySerie($serieId): Response
  {
    $id = filter_var($serieId, FILTER_VALIDATE_INT);
    if ($id === false) {
      return response("Not found", 404);
    }

    $season =  Season::where('serie_id', '=', $id)->firstOrFail();
    return response($season, 200);
  }

  /**
   * Atualiza a temporada de uma série específica
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateBySerie(Request $request, $serieId): Response
  {
    $id = filter_var($serieId, FILTER_VALIDATE_INT);
    if ($id === false) {
      return response("Not found", 404);
    }

    $request->validate([
      'episodeo' => 'min:1',
      'temporada' => 'min:1'
    ]);

    $season =  Season::where('serie_id', '=', $id)->firstOrFail();

    if ($season == null) {
      return response('No content', 204);
    }

    if (isset($request['episodeo'])) {
      $season->episodeo = $request['episodeo'];
    }

    if (isset($request['temporada'])) {
      $season->temporada = $request['temporada'];
    }

    $season->save();

    return response($season, 200);
  }

  /**
   * Exclui o registro de temporadas de uma série
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($serieId): Response
  {
    $id = filter_var($serieId, FILTER_VALIDATE_INT);
    if ($id === false) {
      return response('Not found', 404);
    }

    $season =  Season::where('serie_id', '=', $id)->firstOrFail();

    if ($season == null) {
      return response('No content', 204);
    }

    Season::destroy($id);

    return response('Ok', 200);
  }
}
