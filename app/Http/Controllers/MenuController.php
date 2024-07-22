<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MenuModel;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MenuModel::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm editMenu">Edit</a> ';
                    $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteMenu">Delete</a>';
                    return $btn;
                })
                ->editColumn('gambar', function($row) {
                    return $row->gambar ? asset('storage/' . $row->gambar) : '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('menu')->with('data', MenuModel::all()); // Mengirim data ke view
    }

    public function store(Request $request)
    {
        $input = $request->all();

        if ($request->hasFile('gambar')) {
            $input['gambar'] = $request->file('gambar')->store('images', 'public');
        }

        MenuModel::updateOrCreate(['id' => $request->id], $input);

        return response()->json(['success' => 'Menu saved successfully.']);
    }

    public function edit($id)
    {
        $menu = MenuModel::find($id);
        return response()->json($menu);
    }

    public function destroy($id)
    {
        $menu = MenuModel::find($id);
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }
        $menu->delete();

        return response()->json(['success' => 'Menu deleted successfully.']);
    }
}
