<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    private $params = [
        "basic"=>"admin/profile",
        "dir"=>"admin.profile",
        "route"=>"admin.profile",
        "model"=>"profile",
        "singular_title"=>"Profile",
        "plural_title"=>"Profiles",
        "upload_dir"=>"profile",
        "create_rules"=>[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'role' => 'required',
        ],
        "edit_rules"=>[
            'name' => 'required',
            // 'email' => 'required',
            // 'role' => 'required'
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $row = Auth::getUser();
        $params = $this->params;
        return view($this->params['dir'].".show",compact("row","params"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = Auth::getUser();
        $parm[$this->params['model']] = $id;
        $title = "Edit ".$this->params['singular_title'];
        $params = $this->params;
        return view($this->params['dir'].".edit",compact("row","title","params","parm"));
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
        if($request->has("change_password")){
            $this->params['edit_rules']["password"] = ['required', 'string', 'min:8', 'confirmed'];
        }
        //'password' => 
        $request->validate($this->params['edit_rules']);
        $table = User::find($id);

        $table->name = $request->name;
        //$table->email = $request->email;

        if($request->has("change_password")){
            $table->password = Hash::make($request->password);
        }

        $table->save();

        return redirect(route("admin.profile.index"));
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
