<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgreeController extends Controller
{
    public function agree(Request $request,$id,$add)
    {
        if($add == '1'){
            if(count(DB::select('select * from agree where wall_id = ? and ip = ?', [$id, $request->getClientIp()])) == 0) {
                DB::update('update walls set agree = agree + ? where id = ?', [$add,$id]);
                DB::insert('insert into agree (wall_id,ip) VALUES (?,?)', [$id, $request->getClientIp()]);
            }
        }else{
            if(count(DB::select('select * from agree where wall_id = ? and ip = ?', [$id, $request->getClientIp()]))) {
                DB::update('update walls set agree = agree + ? where id = ?', [$add,$id]);
                DB::delete('delete from agree where wall_id = ? and ip = ?', [$id, $request->getClientIp()]);
            }
        }
    }

}
