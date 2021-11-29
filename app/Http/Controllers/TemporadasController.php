<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temporada;
use Illuminate\Http\Response;

class TemporadasController extends Controller
{
  /**
   * Lista todas os registros encontrados
   *
   * @return \Illuminate\Http\Response
   */
  public function index(): Response
  {
    return response(Temporada::all(), 200);
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
    $request->validate(['numero' => 'required|unique:temporadas']);
    $request->validate(['nome' => 'required|unique:temporadas']);
    $temporadaCadastrada = Temporada::create($request->all());
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

    $temporada =  Temporada::where('serie_id', '=', $id)->firstOrFail();
    return response($temporada, 200);
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
      'numero' => 'min:1',
      'nome' => 'min:1'
    ]);

    $temporada =  Temporada::where('serie_id', '=', $id)->firstOrFail();

    if ($temporada == null) {
      return response('No content', 204);
    }

    if (isset($request['numero'])) {
      $temporada->numero = $request['numero'];
    }

    if (isset($request['nome'])) {
      $temporada->nome = $request['nome'];
    }

    $temporada->save();

    return response($temporada, 200);
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

    $temporada =  Temporada::where('serie_id', '=', $id)->firstOrFail();

    if ($temporada == null) {
      return response('No content', 204);
    }

    temporada::destroy($id);

    return response('Ok', 200);
  }
}
