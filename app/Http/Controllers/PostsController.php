<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'integer|min:0',
            'pageSize' => 'integer|min:1',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);
            }

            $query = DB::table('posts')->where('active', 1);

            if (isset($request['pageSize'])) {
                $query->limit($request['pageSize']);
                if (isset($request['offset'])) $query->offset($request['offset']);
            }

            $posts = $query->get();
            return response()->json(['data' => $posts]);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'server_error', 'errors' => $ex->getMessage()], 500);
        }
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
            'title' => 'required|string|min:1|max:500',
            'body' => 'required|string|min:1|max:65535',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);
            }

            $now = date('Y:m:d H:i:s');

            DB::table('posts')->insert([
                'user_id' => $request['userId'],
                'title' => $request['title'],
                'body' => $request['body'],
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return response()->json(['msg' => 'Post succesfully created.'], 201);
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
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1|exists:posts,id',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);
            }

            $post = DB::table('posts')->where('id', $id)->get()[0];
            return response()->json($post);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'server_error'], 500);
        }
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
        $req = $request->all();
        $req['id'] = $id;

        $validator = Validator::make($req, [
            'id' => 'required|integer|min:1|exists:posts,id',
            'title' => 'required|string|min:1|max:500',
            'body' => 'required|string|min:1|max:65535',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);
            }

            $now = date('Y:m:d H:i:s');

            DB::table('posts')->where('id', $id)->update([
                'title' => $request['title'],
                'body' => $request['body'],
                'updated_at' => $now,
            ]);

            return response()->json(['msg' => 'Post succesfully update.'], 201);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'server_error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1|exists:posts,id',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);
            }

            $now = date('Y:m:d H:i:s');

            DB::table('posts')->where('id', $id)->update([
                'active' => 0,
                'updated_at' => $now,
            ]);

            return response()->json(['msg' => 'Post deleted succesfully.'], 200);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'server_error'], 500);
        }
    }
}
