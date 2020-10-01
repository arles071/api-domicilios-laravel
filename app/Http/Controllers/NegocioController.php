<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Negocio;

class NegocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //listar negocio por id
        $negocio = Negocio::where('neg_nit', '=', $id)->first();
        if(is_object($negocio)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'negocio' => $negocio,
                'negocioUsuario' => $negocio->usuario
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'messaje' => 'No existe un nogocio con este nit'
            ];
        }
        return response()->json($data, $data['code']);
        
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
