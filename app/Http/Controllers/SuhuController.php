<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\Request;
use App\Suhu;

class SuhuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suhus = Suhu::all();

        $response =  $suhus;

        return response()->json($response,200);
    }
    public function indexSuhu(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {

            $suhu = Suhu::select(['id', 'suhu'])->get();

            return DataTables::of($suhu)
            ->make(true);
        }

        $html = $htmlBuilder
        ->addColumn(['data' => 'created_at', 'name' => 'Tanggal', 'title' => 'Tanggal'])
        ->addColumn(['data' => 'suhu', 'name' => 'suhu', 'title' => 'Suhu']);

        return view('suhu.index')->with(compact('html'));
    }
    public function tabel()
    {
        $suhus = Suhu::all();

        $response =  $suhus;

        return response()->json($response,200);
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
            'suhu' => 'required',

        ]);
        $suhu = Suhu::create($request->all());

        if($suhu->save()){
            $message = [
                'suhu' => $suhu
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
