<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReplyController extends Controller
{
    public function showReply(Request $request, $id)
    {
        $wall = DB::select('SELECT * FROM walls where id = ?', [$id])[0];
        $reply = DB::select('SELECT * FROM reply where wall_id = ? ORDER BY create_at DESC ', [$id]);

        $f = DB::select('SELECT * FROM agree where wall_id = ? and ip = ?',[$wall->id,$request->getClientIp()]);
        $wall->onagree = $f?1:0;
        return view('showreply',['colors'=>config('wall')['color'],'res'=>$wall,'replys'=>$reply,'walls'=>config('wall')['tabs'],'wall_id'=>$id]);
    }

    public function addReply(Request $request)
    {
        $name = $request->input('name');
        if ( is_null($request->input('content'))) {
            return redirect('/showreply/'.$request->input('wall_id'));
        }
        if(is_null($name)) $name='匿名';
        DB::insert("insert into reply (wall_id,content,name,ip) values (?,?,?,?)"
            , [$request->input('wall_id'), $request->input('content'), $name, $request->getClientIp()]);
        DB::update('update walls set reply = reply + 1 where id = ?', [$request->input('wall_id')]);
        return redirect('/showreply/'.$request->input('wall_id'));
    }
}
