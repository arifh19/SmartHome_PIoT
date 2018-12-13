<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;
use App\Lampu;

class LampuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lampu = Lampu::all();

        $response =  $lampu;

        return response()->json($response,200);
    }
    public function indexLampu(Request $request, Builder $htmlBuilder)
    {
        $query = Lampu::select(['id', 'status']);
        if ($request->ajax()) {

            $lampu = Lampu::select(['id', 'status'])->get();

            return DataTables::of($lampu)
            ->make(true);
        }

        $html = $htmlBuilder
        ->addColumn(['data' => 'created_at', 'name' => 'Tanggal', 'title' => 'Tanggal'])
        ->addColumn(['data' => 'status', 'name' => 'status', 'title' => 'Status']);

        return view('lampu.index')->with(compact('row'));
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
        $this->validate($request,[
            'status' => 'required',

        ]);
        $lampu = Lampu::create($request->all());

        if($lampu->save()){
            $message = [
                'status' => $lampu
            ];
            return response()->json($message,201);
        }

        $response = [
            'msg' => 'Error during creation',
        ];
        return response()->json($response,404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
