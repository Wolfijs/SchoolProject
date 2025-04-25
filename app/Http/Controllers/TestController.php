<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function checkTables()
    {
        $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
        return response()->json($tables);
    }
}
