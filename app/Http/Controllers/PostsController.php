<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule as ValidationRule;

class PostsController extends Controller
{

    public static function addLikesCountToQuery($query)
    {
        return $query
            ->where('posts.active', 1)
            ->leftJoin('likes_posts_users', 'posts.id', '=', 'likes_posts_users.post_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->groupBy('posts.id');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'integer|min:0',
            'pageSize' => 'integer|min:1|max:10',
            'content' => 'string',
        ]);

        try {
            if ($validator->fails())
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);

            $query = DB::table('posts');
            $query = $this->addLikesCountToQuery($query);

            if (isset($request['content'])) {
                $query->whereRaw('(`title` like "%' . $request['content'] .'%" or `body` like "%' . $request['content'] . '%")');
            }

            if (isset($request['pageSize'])) {
                $query->limit($request['pageSize']);
                if (isset($request['offset'])) $query->offset($request['offset']);
            }

            $posts = $query
                ->selectRaw('posts.id, posts.user_id, users.name as author, posts.title, posts.body, count(likes_posts_users.id) as likes')
                ->get();
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
            if ($validator->fails())
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);

            $now = date('Y:m:d H:i:s');
            DB::table('posts')->insert([
                'user_id' => $request['userId'],
                'title' => trim($request['title']),
                'body' => trim($request['body']),
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
            if ($validator->fails())
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);

            $query = DB::table('posts')->where('posts.id', $id);
            $query = $this->addLikesCountToQuery($query);
            $post = $query->selectRaw('posts.id, posts.user_id, users.name as author, posts.title, posts.body, count(likes_posts_users.id) as likes')
                ->get();
            $result = count($post) > 0 ? $post[0] : [];
            return response()->json($result);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'server_error', 'error' => $ex->getMessage()], 500);
        }
    }

    /**
     * Display post likes.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showLikesByPostId($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1|exists:posts,id',
        ]);

        try {
            if ($validator->fails()) {
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 422);
            }

            $likes = DB::table('likes_posts_users', 'lku')
                ->join('users', 'lku.user_id', '=', 'users.id')
                ->where('post_id', $id)
                ->select('users.name', 'lku.*')->get();


            return response()->json(['data' => $likes]);
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
            'title' => 'required|string|min:1|max:500',
            'body' => 'required|string|min:1|max:65535',
        ]);

        try {
            if ($validator->fails())
                return response()->json(['msg' => 'bad_request', 'errors' => $validator->errors()], 400);
            
                $isUserOwner = DB::table('posts')->where('id', $id)->get();
                if(auth()->user()->id != $isUserOwner[0]->user_id)
                    return response()->json(['msg' => 'No permisions'], 400);


            if (DB::table('posts')->where('id', $id)->get('active')[0]->active === 0)
                return response()->json(['msg' => 'Post no active'], 422);

            DB::table('posts')->where('id', $id)->update([
                'title' => trim($request['title']),
                'body' => trim($request['body']),
                'updated_at' =>  date('Y:m:d H:i:s'),
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
        try {
            $isUserOwner = DB::table('posts')->where('id', $id)->get();
            if (auth()->user()->id != $isUserOwner[0]->user_id)
                return response()->json(['msg' => 'No permisions'], 400);

            DB::table('posts')->where('id', $id)->update([
                'active' => 0,
                'updated_at' => date('Y:m:d H:i:s'),
            ]);

            return response()->json(['msg' => 'Post deleted succesfully.'], 200);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'server_error'], 500);
        }
    }
}
