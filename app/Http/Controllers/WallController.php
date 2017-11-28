<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WallController extends Controller
{
    public function showWall(Request $request,$type=0)
    {
        //DB::update('update countview set count = count + 1 limit 1');
        return view('showWall', ['walls' => config('wall'), 'ip' => $request->getClientIp(),'type' => $type]);
    }
    public function ajWall(Request $request,$type = 0, $last = 0)
    {
        if (!$type) {
            if($last){
                $results = DB::select('SELECT * FROM walls where id < ? ORDER by id desc limit 10', [$last]);
            }else{
                $results = DB::select('SELECT * FROM walls ORDER by id desc limit 10');
            }
        } else {
            if($last){
                $results = DB::select('SELECT * FROM walls where type = ? and id < ? ORDER by id desc limit 10', [$type,$last]);
            }else{
                $results = DB::select('SELECT * FROM walls where type = ? ORDER by id desc limit 10',[$type]);
            }
        }

        foreach ($results as &$res){
            $f = DB::select('SELECT * FROM agree where wall_id = ? and ip = ?',[$res->id,$request->getClientIp()]);
            $res->onagree = $f?1:0;
        }
        return view('ajWall', ['results' => $results, 'walls' => config('wall')['tabs'], 'colors' => config('wall')['color']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addWall(Request $request)
    {
        $file=$request->file('image');
        if($file) {
            $validator = Validator::make(['image' => $file], ['image' => 'image']);
            if ($validator->fails()) {
                $file = null;
            }

            $img = Image::make($file->getRealPath());
            $img->resize(350, 350);
            $md5 = md5($img->encode('png'));
            $img->save('image/' . $md5 . '.png');
            $file = $md5 . '.png';
        }
        $type = $request->input('type');
        $name = $request->input('name');
        $t = config('wall');
        if (! array_key_exists($type,$t['tabs']) || is_null($request->input('content'))) {
            return redirect('/');
        }
        if(is_null($name)) $name='匿名';
        DB::insert("insert into walls (type,content,name,ip,image) values (?,?,?,?,?)"
            , [$type, $request->input('content'), $name, $request->getClientIp(),$file]);
        return redirect('/'.$type);
    }

    public function image(Request $request){
        $file=$request->file('image');

        $validator = Validator::make(['image' => $file], ['image' => 'image']);
        if ( $validator->fails() ) {
            return Response::json([
                'success' => false,
                'errors' => '不是图片'
            ]);
        }

        $img = Image::make($file->getRealPath());
        $img->resize(350, 350);
        $md5 = md5($img->encode('png'));
        $img->save('image/'.$md5.'.png');
        return Response::json([
            'success' => true,
            'image' => asset('image/'.$md5.'.png')
        ]);
    }
}
