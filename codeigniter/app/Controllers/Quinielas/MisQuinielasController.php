<?php

namespace App\Controllers\Quinielas;

use App\Controllers\BaseController;
use App\Models\QuinielasModel;
use App\Models\LeaguesModel;
use App\Models\UserModel;
use DateTime;

class MisQuinielasController extends BaseController
{
    function __construct()
	{
        $this->quinielasModel = new QuinielasModel();
        $this->leaguesModel = new LeaguesModel();
        $this->userModel = new UserModel();
	}

    public function index()
    {
        $session = service('session');
        
        /* GENERALES */
        $data['title'] = 'Mis Quinielas';
        $data['HTMLModules'] = ['bootstrap', 'jquery', 'js', 'globalCSS', 'fontawesome', 'logout', 'validate', 'modal'];
        $data['js'] = 'quinielas/mis-quinielas.js';
        $data['menuResult'] = $session->get('menu');
        $data['error'] = view('Templates/error');
        $data['success'] = view('Templates/success');

        /* CATALOGOS */

        /* MODALES */
        // $data['modalDelete'] = view('Templates/modalDelete', $data);

        // $data['formEdit'] = '';
        // $data['modalEdit'] = view('Templates/modalEdit', $data);

        /* DATA */
        $data['dataTable'] = $this->quinielas();

        /* VISTAS */
        echo view('Templates/header', $data);
        echo view('Templates/menu', $data);
        echo view('Quinielas/MisQuinielas/index', $data);
        echo view('Templates/footer', $data);
    }

    public function getGlobal()
    {
        $data['status'] = "OK";
        $data['message'] = "";

        $quiniela['quiniela_id'] = $this->request->getPost('quinielaId') !== null ? trim(util_decode($this->request->getPost('quinielaId'))) : 0;

        $participantes = $this->quinielasModel->getParticipantes($quiniela);
        $partidos = $this->quinielasModel->getParticipantesPartidos($quiniela);
        $quiniela = $this->quinielasModel->getQuinielaById($quiniela);

        $quinielaRow = $quiniela->getRowArray();
        $league['id'] = $quinielaRow['liga'];
        $league['season'] = $quinielaRow['temporada'];
        $league['rounds'] = explode('|', $quinielaRow['rondas']);
        $fixtures = $this->leaguesModel->getFixtures($league);
        $fixtures = util_arraySort($fixtures, 'date', SORT_ASC);

        $data['participantes'] = $this->calcularPuntos($participantes, $partidos, $fixtures);
        $data['participantes'] = util_arraySort($data['participantes'], 'puntos', SORT_DESC);

        $data['mostrar_resultados'] = $quinielaRow['fecha_inicio'] > date('Y-m-d') ? false : true;
        $data['inTime'] = date_format(new DateTime($quinielaRow['fecha_inicio']), "c") >= date_format(new DateTime(), "c") ? true : false;
        $data['dataTable'] = view('Quinielas/MisQuinielas/scores', $data);

        return json_encode($data);
    }

    public function getPorPartido()
    {
        $data['status'] = "OK";
        $data['message'] = "";

        $quiniela['quiniela_id'] = $this->request->getPost('quinielaId') !== null ? trim(util_decode($this->request->getPost('quinielaId'))) : 0;

        $quiniela = $this->quinielasModel->getQuinielaById($quiniela);

        $quinielaRow = $quiniela->getRowArray();
        $league['id'] = $quinielaRow['liga'];
        $league['season'] = $quinielaRow['temporada'];
        $league['rounds'] = explode('|', $quinielaRow['rondas']);
        $data['fixtures'] = $this->leaguesModel->getFixtures($league);
        $data['fixtures'] = util_arraySort($data['fixtures'], 'date', SORT_ASC);

        $data['dataTable'] = view('Quinielas/MisQuinielas/byFixture', $data);

        return json_encode($data);
    }

    public function getResultadosPorPartido()
    {
        $data['status'] = "OK";
        $data['message'] = "";

        $quiniela['quiniela_id'] = $this->request->getPost('quinielaId') !== null ? trim(util_decode($this->request->getPost('quinielaId'))) : 0;
        $league['fixture'] = $this->request->getPost('partidoId') !== null ? trim(util_decode($this->request->getPost('partidoId'))) : 0;

        $participantes = $this->quinielasModel->getParticipantes($quiniela);
        $partidos = $this->quinielasModel->getParticipantesPartidos($quiniela);
        $quiniela = $this->quinielasModel->getQuinielaById($quiniela);

        $quinielaRow = $quiniela->getRowArray();
        $league['id'] = $quinielaRow['liga'];
        $league['season'] = $quinielaRow['temporada'];
        $league['rounds'] = explode('|', $quinielaRow['rondas']);
        $data['fixtures'] = $this->leaguesModel->getFixture($league);
        $data['fixtures'] = util_arraySort($data['fixtures'], 'date', SORT_ASC);

        log_message('debug', '[getResultadosPorPartido] quiniela_id={0} fixture={1} fixtures count={2}', [$quinielaRow['quiniela_id'], $league['fixture'], count($data['fixtures'])]);

        $data['participantes'] = $this->calcularPuntos($participantes, $partidos, $data['fixtures']);
        $data['participantes'] = util_arraySort($data['participantes'], 'puntos', SORT_DESC);

        $data['marcador'] = view('Quinielas/MisQuinielas/score', $data);
        $fixtureData = $data['fixtures'][$league['fixture']] ?? null;
        $data['mostrar_resultados'] = $fixtureData !== null && !is_null($fixtureData['home_goals']) && !is_null($fixtureData['away_goals']);
        $data['inTime'] = date_format(new DateTime($quinielaRow['fecha_inicio']), "c") >= date_format(new DateTime(), "c") ? true : false;
        $data['dataTable'] = view('Quinielas/MisQuinielas/scores', $data);

        return json_encode($data);
    }

    private function calcularPuntos($participantes, $partidos, $fixtures)
    {
        $results = array();

        $participantesArray = $participantes->getResultArray();
        $partidosArray = $partidos->getResultArray();

        foreach($participantesArray as $participante)
        {
            $result = array();
            $result['usuario_avatar'] = $participante['usuario_avatar'];
            $result['usuario_nombre'] = $participante['usuario_nombre'];
            $result['usuario_apellido_paterno'] = $participante['usuario_apellido_paterno'];
            $result['usuario_apellido_materno'] = $participante['usuario_apellido_materno'];
            $result['pronostico_consecutivo'] = $participante['pronostico_consecutivo'];
            $result['puntos'] = 0;

            foreach ($fixtures as $fixture)
            {
                foreach ($partidosArray as $partido)
                {
                    if($participante['usuario_id'] == $partido['usuario_id'] && $participante['pronostico_consecutivo'] == $partido['pronostico_consecutivo'] )
                    {
                        if($fixture['id'] == $partido['partido_partido'])
                        {
                            $result['partido_pronostico_local'] = $partido['partido_pronostico_local'];
                            $result['partido_pronostico_visitante'] = $partido['partido_pronostico_visitante'];

                            if(($partido['partido_pronostico_local'] !== null && $partido['partido_pronostico_visitante'] !== null)
                                && ($fixture['home_goals'] !== null && $fixture['away_goals'] !== null))
                            {
                                if($fixture['home_goals'] == $partido['partido_pronostico_local'] && $fixture['away_goals'] == $partido['partido_pronostico_visitante'])
                                {
                                    $result['puntos'] += 3;
                                }
                                elseif($fixture['home_goals'] == $fixture['away_goals'] && $partido['partido_pronostico_local'] == $partido['partido_pronostico_visitante'])
                                {
                                    $result['puntos'] += 1;
                                }
                                elseif($fixture['home_goals'] > $fixture['away_goals'] && $partido['partido_pronostico_local'] > $partido['partido_pronostico_visitante'])
                                {
                                    $result['puntos'] += 1;
                                }
                                elseif($fixture['home_goals'] < $fixture['away_goals'] && $partido['partido_pronostico_local'] < $partido['partido_pronostico_visitante'])
                                {
                                    $result['puntos'] += 1;
                                }
                            }
                        }
                    }
                }
            }

            $results[] = $result;
        }

        return $results;
    }

    public function getAdmin()
    {
        $data['status'] = "OK";
        $data['message'] = "";

        $quiniela['usuario_id'] = getUserSession();
        $quiniela['quiniela_id'] = $this->request->getPost('quinielaId') !== null ? trim(util_decode($this->request->getPost('quinielaId'))) : 0;

        $data['participantes'] = $this->quinielasModel->getParticipantes($quiniela);
        $data['quiniela'] = $this->quinielasModel->getQuiniela($quiniela);

        $quinielaRow = $data['quiniela']->getRowArray();

        $data['url'] = base_url('/participant/add?qid=' . $quinielaRow['url_encode']);

        $data['dataTable'] = view('Quinielas/MisQuinielas/admin', $data);

        return json_encode($data);
    }

    public function savePronosticos()
    {
        $data['status'] = "OK";
        $data['message'] = "Pronósticos guarados con éxito";

        $pronosticoId = $this->request->getPost('pronosticoId') !== null ? trim(util_decode($this->request->getPost('pronosticoId'))) : 0;
        $partidos = $this->request->getPost('partido') !== null ? $this->request->getPost('partido') : 0;

        $fixtures = array();

        foreach($partidos as $key => $partido)
        {
            $fixture = array();
            $fixture['id'] = util_decode($key);
            $fixture['pronostico_id'] = $pronosticoId;
            $fixture['partido'] = util_decode($partido['partido']);
            $fixture['pronostico_local'] = $partido['home'] != '' ? $partido['home'] : null;
            $fixture['pronostico_visitante'] = $partido['away'] != '' ? $partido['away'] : null;

            $fixtures[] = $fixture;
        }

        $data['fixtures'] = $fixtures;
        $this->quinielasModel->savePartidos($fixtures);

        return json_encode($data);
    }

    public function savePronostico()
    {
        $data['status'] = "OK";
        $data['message'] = "Pronóstico activado con éxito";

        $pronostico['id'] = $this->request->getPost('pid') !== null ? trim(util_decode($this->request->getPost('pid'))) : 0;
        $pronosticos = $this->request->getPost('pronostico') !== null ? $this->request->getPost('pronostico') : array();
        $pronostico['quiniela_id'] = $this->request->getPost('quinielaId') !== null ? trim(util_decode($this->request->getPost('quinielaId'))) : 0;
        
        $on = array();
        foreach($pronosticos as $key => $val)
        {
            $on[] = util_decode($key);
        }

        $pronostico['activo'] = in_array($pronostico['id'], $on) ? true : false;

        $data['message'] = $pronostico['activo'] ? "Pronóstico activado con éxito" : "Pronóstico desactivado con éxito";

        $this->quinielasModel->savePronostico($pronostico);

        return json_encode($data);
    }

    public function getPronosticos()
    {
        $data['status'] = "OK";
        $data['message'] = "Todo bien";

        $quiniela['usuario_id'] = getUserSession();
        $quiniela['quiniela_id'] = $this->request->getPost('qid') !== null ? trim($this->request->getPost('qid')) : 0;

        $data['pronosticos'] = $this->quinielasModel->getPronosticos($quiniela);
        $data['quiniela'] = $this->quinielasModel->getQuiniela($quiniela);

        $quinielaRow = $data['quiniela']->getRowArray();
        $data['subTitle'] = 'Quiniela: ' . $quinielaRow['quiniela_nombre'];
        
        $data['mainTable'] = view('Quinielas/MisQuinielas/quiniela', $data);
        $data['dataTable'] = view('Quinielas/MisQuinielas/dataTable', $data);

        return json_encode($data);
    }

    public function getPartidos()
    {
        $data['status'] = "OK";
        $data['message'] = "Todo bien";

        try
        {
            $search['usuario_id'] = getUserSession();
            $search['quiniela_id'] = $this->request->getPost('quinielaId') !== null ? trim(util_decode($this->request->getPost('quinielaId'))) : 0;
            $search['pronostico_id'] = $this->request->getPost('pronosticoId') !== null ? trim(util_decode($this->request->getPost('pronosticoId'))) : 0;

            log_message('debug', '[getPartidos] quiniela_id={0} pronostico_id={1}', [$search['quiniela_id'], $search['pronostico_id']]);

            $data['quiniela'] = $this->quinielasModel->getQuiniela($search);
            $data['partidos'] = $this->quinielasModel->getPartidos($search);
            $data['pronostico'] = $this->quinielasModel->getPronostico($search);

            $quinielaRow = $data['quiniela']->getRowArray();
            log_message('debug', '[getPartidos] quinielaRow={0}', [json_encode($quinielaRow)]);

            $league['id'] = $quinielaRow['liga'];
            $league['season'] = $quinielaRow['temporada'];
            $league['rounds'] = explode('|', $quinielaRow['rondas']);

            $fixturePath = WRITEPATH . "data/fixtures/{$league['id']}-{$league['season']}.json";
            log_message('debug', '[getPartidos] fixture path={0} exists={1}', [$fixturePath, file_exists($fixturePath) ? 'yes' : 'no']);
            log_message('debug', '[getPartidos] rounds={0}', [json_encode($league['rounds'])]);

            $data['fixtures'] = $this->leaguesModel->getFixtures($league);
            log_message('debug', '[getPartidos] fixtures count={0}', [count($data['fixtures'])]);

            $data['fixtures'] = util_arraySort($data['fixtures'], 'date', SORT_ASC);
            $data['admin'] = $this->userModel->getUserDataById($quinielaRow['quiniela_usuario_id']);

            $partidosArray      = $data['partidos']->getResultArray();
            $existingFixtureIds = array_column($partidosArray, 'partido');
            $fixtureIds         = array_column(array_values($data['fixtures']), 'id');
            $missingFixtureIds  = array_diff($fixtureIds, $existingFixtureIds);

            if(!empty($missingFixtureIds))
            {
                log_message('debug', '[getPartidos] missing partidos, inserting count={0}', [count($missingFixtureIds)]);

                $partidos = [];
                foreach($missingFixtureIds as $fixtureId)
                {
                    $partido = [];
                    $partido["pronostico_id"] = $search['pronostico_id'];
                    $partido["partido"] = $fixtureId;

                    $partidos[] = $partido;
                }

                log_message('debug', '[getPartidos] sample partido to insert={0}', [json_encode($partidos[0] ?? null)]);

                $this->quinielasModel->newPartidos($partidos);
                $data['partidos'] = $this->quinielasModel->getPartidos($search);
                $partidosArray    = $data['partidos']->getResultArray();
            }
            log_message('debug', '[getPartidos] partido IDs in DB={0}', [json_encode(array_column($partidosArray, 'partido'))]);
            log_message('debug', '[getPartidos] fixture IDs={0}', [json_encode($fixtureIds)]);

            $data['dataTable'] = view('Quinielas/MisQuinielas/partidos', $data);
        }
        catch (\Throwable $e)
        {
            log_message('error', '[getPartidos] EXCEPTION: {0} in {1}:{2}', [$e->getMessage(), $e->getFile(), $e->getLine()]);
            $data['status']  = 'ERROR';
            $data['message'] = 'Error interno: ' . $e->getMessage();
        }

        return json_encode($data);
    }

    public function saveQuiniela()
    {
        $data['status'] = 'OK';
        $data['message'] = 'Quiniela creada con éxito';
        $liga         = $this->request->getPost('ligaId') !== null ? (int) trim($this->request->getPost('ligaId')) : 0;
        $temporada    = $this->request->getPost('temporada') !== null ? trim($this->request->getPost('temporada')) : '';
        $nombre       = $this->request->getPost('nombre') !== null ? trim($this->request->getPost('nombre')) : '';
        $fechaInicio  = $this->request->getPost('fechaInicio') !== null ? trim($this->request->getPost('fechaInicio')) : '';
        $rondas       = $this->request->getPost('rondas') !== null ? trim($this->request->getPost('rondas')) : '';
        $maxPronosticos = $this->request->getPost('maxPronosticos') !== null ? (int) trim($this->request->getPost('maxPronosticos')) : 1;

        if (!$liga || !$temporada || !$nombre || !$fechaInicio || !$rondas)
        {
            $data['status'] = 'ERROR';
            $data['message'] = 'Todos los campos son obligatorios';
            return json_encode($data);
        }

        $quiniela = [
            'usuario_id'       => getUserSession(),
            'tipo_quiniela_id' => 1,
            'liga'             => $liga,
            'temporada'        => $temporada,
            'nombre'           => $nombre,
            'fecha_inicio'     => date('Y-m-d H:i:s', strtotime($fechaInicio)),
            'rondas'           => $rondas,
            'max_pronosticos'  => max(1, min(10, $maxPronosticos)),
            'url_encode'       => bin2hex(random_bytes(64)),
        ];

        $quinielaId = $this->quinielasModel->newQuiniela($quiniela);

        $pronostico = [
            'quiniela_id' => $quinielaId,
            'usuario_id'  => getUserSession(),
            'consecutivo' => 1,
            'activo'      => 1,
        ];

        $this->quinielasModel->newPronosticos([$pronostico]);

        return json_encode($data);
    }

    public function getQuinielaData()
    {
        $data['status'] = 'OK';
        $data['message'] = '';

        $quinielaId = $this->request->getPost('qid') !== null ? (int) trim(util_decode($this->request->getPost('qid'))) : 0;

        $result = $this->quinielasModel->getQuinielaById(['quiniela_id' => $quinielaId]);
        $row    = $result->getRowArray();

        if (!$row || $row['quiniela_usuario_id'] != getUserSession())
        {
            $data['status']  = 'ERROR';
            $data['message'] = 'No autorizado';
            return json_encode($data);
        }

        $data['quiniela'] = [
            'liga'             => $row['liga'],
            'temporada'        => $row['temporada'],
            'nombre'           => $row['quiniela_nombre'],
            'fecha_inicio'     => date('Y-m-d\TH:i', strtotime($row['fecha_inicio'])),
            'rondas'           => $row['rondas'],
            'max_pronosticos'  => $row['max_pronosticos'],
        ];

        return json_encode($data);
    }

    public function updateQuiniela()
    {
        $data['status']  = 'OK';
        $data['message'] = 'Quiniela actualizada con éxito';

        $quinielaId     = $this->request->getPost('quinielaId') !== null ? (int) trim(util_decode($this->request->getPost('quinielaId'))) : 0;
        $nombre         = $this->request->getPost('nombre') !== null ? trim($this->request->getPost('nombre')) : '';
        $fechaInicio    = $this->request->getPost('fechaInicio') !== null ? trim($this->request->getPost('fechaInicio')) : '';
        $rondas         = $this->request->getPost('rondas') !== null ? trim($this->request->getPost('rondas')) : '';
        $maxPronosticos = $this->request->getPost('maxPronosticos') !== null ? (int) trim($this->request->getPost('maxPronosticos')) : 1;

        if (!$quinielaId || !$nombre || !$fechaInicio || !$rondas)
        {
            $data['status']  = 'ERROR';
            $data['message'] = 'Todos los campos son obligatorios';
            return json_encode($data);
        }

        $quiniela = [
            'id'              => $quinielaId,
            'usuario_id'      => getUserSession(),
            'nombre'          => $nombre,
            'fecha_inicio'    => date('Y-m-d H:i:s', strtotime($fechaInicio)),
            'rondas'          => $rondas,
            'max_pronosticos' => max(1, min(10, $maxPronosticos)),
        ];

        $this->quinielasModel->updateQuiniela($quiniela);

        return json_encode($data);
    }

    public function deleteQuiniela()
    {
        $data['status']  = 'OK';
        $data['message'] = 'Quiniela eliminada con éxito';

        $quinielaId = $this->request->getPost('quinielaId') !== null ? (int) trim(util_decode($this->request->getPost('quinielaId'))) : 0;

        if (!$quinielaId)
        {
            $data['status']  = 'ERROR';
            $data['message'] = 'Quiniela no válida';
            return json_encode($data);
        }

        try
        {
            $deleted = $this->quinielasModel->deleteQuiniela(['id' => $quinielaId, 'usuario_id' => getUserSession()]);

            if (!$deleted)
            {
                $data['status']  = 'ERROR';
                $data['message'] = 'No tienes permiso para eliminar esta quiniela';
            }
        }
        catch (\Throwable $e)
        {
            $data['status']  = 'ERROR';
            $data['message'] = 'Error al eliminar: ' . $e->getMessage();
        }

        return json_encode($data);
    }

    private function quinielas()
    {
        $data['subTitle'] = 'Mis Quinielas';
        $data['quinielas'] = $this->quinielasModel->getQuinielas(getUserSession());
        $data['leagues'] = $this->leaguesModel->getLeagues();
        $data['mainTable'] = view('Quinielas/MisQuinielas/quinielas', $data);
        $data['formNuevaQuiniela'] = view('Quinielas/MisQuinielas/formNuevaQuiniela', $data);
        $data['dataTable'] = view('Quinielas/MisQuinielas/dataTable', $data);

        return $data['dataTable'];
    }
}
