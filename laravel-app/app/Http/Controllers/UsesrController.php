<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsesrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginator = User::paginate(10);
        return Array(
            "Users" => $paginator->items(),
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::find($id);
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
            'email' => ['required', 'email'],
            'name' => ['required', "string"],
            'surname' => ['required', "string"],
            'role' => ['required', "numeric"],
        ]);

        if ($credentials->fails()) {
            return response()->json([
                "Message" => "Nie poprawny format danych!",
                "Status" => "NO"
            ]);
        }

        $credentials = $credentials->validated();

        $user = User::find($id);

        if ($user == null) {
            return response()->json([
                "Message" => "nie istnieje!",
                "Status" => "NO"
            ]);
        }

        $user->email = $credentials["email"];
        $user->name = $credentials["name"];
        $user->surname = $credentials["surname"];
        $user->role = $credentials["role"];
        $user->save();

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
        User::find($id)->delete();
    }
}
