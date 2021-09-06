<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer|min:1|exists:users,id',
            'postId' => 'required|integer|min:1|exists:posts,id',
        ]);

        try {
            if ($validator->fails())
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);

            $postIsActiveQuery = DB::table('posts')->where('id', $request['postId'])->get('active')[0]->active;
            if (!$postIsActiveQuery) return response()->json(['msg' => 'Post no active'], 422);

            $queryGetLike = DB::table('likes_posts_users')
                ->where('user_id', $request['userId'])
                ->where('post_id', $request['postId'])
                ->limit(1)
                ->select('id')->get();

            //Insert
            if (count($queryGetLike) === 0) {
                $now = date('Y:m:d H:i:s');
                DB::table('likes_posts_users')->insert([
                    'user_id' => $request['userId'],
                    'post_id' => $request['postId'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                return response()->json(['msg' => 'Like succesfully created.', 'data' => ['like' => 1]], 201);
            }

            //Delete
            DB::table('likes_posts_users')->delete($queryGetLike[0]->id);
            return response()->json(['msg' => 'Like succesfully deleted.', 'data' => ['like' => 0]], 200);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'server_error'], 500);
        }
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
