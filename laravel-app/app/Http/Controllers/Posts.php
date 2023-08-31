<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Tags;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Posts extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::query();
        
        if ($request->has('tag')) {
            $query
                ->join("posts_tags", "posts_tags.post_id", "=","posts.id")
                ->where('posts_tags.tag_id', '=', $request->input('tag'));
        }

        if ($request->has('sort_by')) {
            $sortField = $request->input('sort_by', 'ID');
            $sortDirection = $request->input('sort_dir', 'desc'); // default to descending
            $query->orderBy($sortField, $sortDirection);
        }

        $paginator = $query->paginate(10);
        return Array(
            "Posts" => $paginator->items(),
            "lastPage" => $paginator->lastPage(),
            "hasPages" => $paginator->hasPages(),
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'Tittle' => ['required', 'string'],
            'Data' => ['required', "string"],
            'Tags' => ["array"],
            'Tags.*' => ["string"],
        ]);


        if ($credentials->fails()) {
            return response()->json([
                "Message" => "Nie poprawny format danych!",
                "Status" => "NO"
            ]);
        }

        $credentials = $credentials->validated();

        $post = new Post();
        $post->title = $credentials["Tittle"];
        $post->content = $credentials["Data"];
        $post->created_by = Auth::user()->id;
        $post->save();

        foreach($credentials["Tags"] as $val) {
            $tag = Tag::firstOrCreate(["name" => $val]);
            Tags::firstOrCreate(["post_id" => $post->id,"tag_id" => $tag->id]);
        }

        return response()->json([
            "Message" => "",
            "Status" => "OK"
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        return response()->json([
            "Status" => "OK",
            "Tags" => $post->Tags,
            "Tittle" => $post->title,
            "Data" => $post->content,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $credentials = Validator::make($request->all(), [
            'Tittle' => ['required', 'string'],
            'Data' => ['required', "string"],
            'Tags' => ["array"],
            'Tags.*' => ["string"],
        ]);


        if ($credentials->fails()) {
            return response()->json([
                "Message" => "Nie poprawny format danych!",
                "Status" => "NO"
            ]);
        }

        $credentials = $credentials->validated();

        $post = Post::find($id);

        if ($post == null) {
            return response()->json([
                "Message" => "Nie poprawny format danych!",
                "Status" => "NO"
            ]);
        }

        $post->title = $credentials["Tittle"];
        $post->content = $credentials["Data"];
        $post->save();

        foreach($credentials["Tags"] as $val) {
            $tag = Tag::firstOrCreate(["name" => $val]);
            Tags::firstOrCreate(["post_id" => $post->id,"tag_id" => $tag->id]);
        }

        return response()->json([
            "Message" => "",
            "Status" => "OK"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('posts_tags')->where('post_id', $id)->delete();
        Post::find($id)->delete();
    }

    public function allTags()
    {
        return Tag::all();
    }

    public function Search(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'text' => ['required', 'string']            
        ]);

        if ($credentials->fails()) {
            return response()->json([
                "Message" => "Nie poprawny format danych!",
                "Status" => "NO"
            ]);
        }

        $credentials = $credentials->validated();

        $user = User::select('id', 'name', 'surname as restdata')
        ->where('name', "like", '%' . $credentials["text"] . '%')
        ->orWhere('surname', "like", '%' . $credentials["text"] . '%');

        $post = Post::select('id', 'title as name', 'title as restdata')
        ->where('title', "like", '%' . $credentials["text"] . '%')
        ->union($user)
        ->get();

        return response()->json([
            "Message" => "",
            "Status" => "OK",
            "Data" => $post
        ]);
    }
}
