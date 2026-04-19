<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\Role;
use App\Models\Permission;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Helper\DatatableHelper;
use App\Models\Office;

class UserController extends AdminController
{
    private $params = [
        "basic" => "admin/users",
        "dir" => "admin.users",
        "route" => "admin.users",
        "model" => "user",
        "singular_title" => "User",
        "plural_title" => "Users",
        "module_name" => "users",
        "upload_dir" => "users",
        "create_rules" => [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'office_id' => ['required'],
            // 'role' => 'required',
        ],
        "edit_rules" => [
            'name' => 'required',
            'email' => 'required',
            'office_id' => 'required',
            // 'role' => 'required'
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'name', "name" => 'Name'],
            ["data" => 'email', "name" => 'Email'],
            ["data" => 'action', "name" => 'Action', "orderable" => "false", "searchable" => "false"],
        ]
    ];

    public function __construct()
    {
        parent::__construct($this->params["module_name"]);
    }

    function find($id = 0)
    {
        if ($id) {
            return User::find($id);
        } else {
            return new User;
        }
    }

    function all($columns = "*")
    {
        return User::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {

        $table = $this->find($id);

        $table->name = $request->name;
        $table->email = $request->email;
        $table->office_id = $request->office_id;
        $table->type = $request->type;

        if ($id == 0) {
            $table->password = Hash::make($request->password);
        }

        if ($request->has("change_password")) {
            $table->password = Hash::make($request->password);
        }

        $table->save();

        //$role = Role::find($request->role);
        // $table->roles()->detach();
        // $table->roles()->attach($role);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->all();
            $table = new DatatableHelper($data, $this->params);
            return $table->table_response();
        }
        $params = $this->params;
        return view($this->params['dir'] . ".index", compact("params"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $params = $this->params;
        $title = "New " . $params["singular_title"];
        $roles = Role::all();
        $offices = Office::all();
        return view($this->params['dir'] . ".create", compact("title", "params", "roles", "offices"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        $request->validate($this->params['create_rules']);
        $this->onHandleOperation($request);

        return redirect(route($this->params['dir'] . ".index"));
    }

    function uploadFile($file, $destinationPath)
    {
        //Display File Name
        $fileName = time() . '_' . $file->getClientOriginalName();

        //Display File Extension
        $ext = $file->getClientOriginalExtension();

        //Display File Real Path
        $realPath = $file->getRealPath();

        //Display File Size
        $size = $file->getSize();

        //Display File Mime Type
        $mimeType = $file->getMimeType();

        //Move Uploaded File
        //        $destinationPath = 'uploads/Auction/attachment';

        $path = $file->move($destinationPath, $fileName);

        return $destinationPath . "/" . $fileName;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $params = $this->params;
        $roles = Role::all();
        return view($this->params['dir'] . ".show", compact("row", "parm", "params", "roles"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $title = "Edit " . $this->params['singular_title'];
        $params = $this->params;
        $roles = Role::all();
        $offices = Office::all();
        return view($this->params['dir'] . ".create", compact("row", "title", "params", "parm", "roles", "offices"));
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
        $data = [];

        $request->validate($this->params['edit_rules']);

        $this->onHandleOperation($request, $id);

        return redirect(route($this->params['dir'] . ".index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $row = $this->find($id);
        if (file_exists($row->featured)) {
            unlink($row->featured);
        }

        if (file_exists($row->attachment)) {
            unlink($row->attachment);
        }
        $row->delete();
        if ($request->ajax()) {

            return Response()->json(["status" => "ok", "message" => "Delete Successfully"]);
        }

        return redirect(route($this->params['dir'] . ".index"));
    }

    public function permission($id)
    {
        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $params = $this->params;
        // dd($row->roles->pluck("id"));
        $modules = Module::select("id", "name", "slug")->get();
        $roles = Role::whereNotIn("id", $row->roles->pluck("id"))->select("id", "name", "slug")->get();
        return view($this->params['dir'] . ".permission", compact("row", "parm", "params", "modules", "roles"));
    }

    public function savePermission(Request $request, $id)
    {
        $user = User::find($id);
        $user->permissions()->detach();
        $permissions = $request->get("permission");
        if ($permissions)
            foreach ($permissions as $key => $permission) {
                $temp = Permission::where("slug", $permission)->first();
                if ($temp) {
                    $user->permissions()->attach($temp);
                }
            }
        return redirect()->back()->with('info', 'Role updated!');
        // return redirect()->back();
    }

    public function saveRoles(Request $request, $id)
    {
        $user = User::find($id);
        $roles = $request->get("roles");
        $userRoles = [];
        if ($request->get("user-roles") != "")
            $userRoles = explode(",", $request->get("user-roles"));
        //dd($user->roles);
        $user->roles()->detach();
        foreach ($userRoles as $role) {
            $user->roles()->attach($role);
        }
        return redirect()->back();
    }

    public function loginAs(Request $request){
        $user = User::find($request->user);
        Auth::login($user);

        return redirect("admin/dashboard");
    }

    public function switchOffice(Request $request){
        $user = Auth::getUser();

        $user->post_id = $request->post_id;

        $user->save();

        // dd($user);

        return redirect("admin/dashboard");
    }
}