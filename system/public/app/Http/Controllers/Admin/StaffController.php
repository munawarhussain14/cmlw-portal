<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helper\DatatableHelper;
use App\Models\Staff;
use App\Models\Office;
use App\Models\Post;
use App\Models\User;

class StaffController extends AdminController
{
    private $params = [
        "basic" => "admin/staffs",
        "dir" => "admin.staffs",
        "route" => "admin.staffs",
        "model" => "staff",
        "singular_title" => "Staff",
        "plural_title" => "Staffs",
        "module_name" => "staffs",
        "upload_dir" => "staffs",
        "create_rules" => [
            "user_id" => "",
            "office_id" => "required",
            "post_id" => "required",
            "access" => "required"
        ],
        "edit_rules" => [
            "user_id" => "",
            "office_id" => "required",
            "post_id" => "required",
            "access" => "required",
        ],
        "columns" => [
            ["data" => 'id', "name" => 'ID'],
            ["data" => 'user_id', "name" => 'User'],
            ["data" => 'post_id', "name" => 'Post'],
            ["data" => 'office_id', "name" => 'Office'],
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
            return Staff::find($id);
        } else {
            return new Staff;
        }
    }

    function all($columns = "*")
    {
        return Staff::select($columns);
    }

    function getDate($date)
    {
        $temp = explode("/", $date);
        return date_create("$temp[1]/$temp[0]/$temp[2]");
    }

    function onHandleOperation($request, $id = 0)
    {
        $table = $this->find($id);

        $table->post_id = $request->post_id;
        $table->access = $request->access;
        $table->office_id = $request->office_id;

        $user_id = 0;
        if($request->user_id){
            $table->user_id = $request->user_id;
            $user_id = $request->user_id;
        }

        $table->save();

        if($user_id){
            $user = User::find($user_id);
            if(!$user->post_id){
                $user->post_id = $table->id;
                $user->save();
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $status = null;
            $districts = null;
            if ($request->has("status")) {
                $status = $request->status;
            }

            if ($request->has("districts")) {
                $districts = $request->districts;
            }

            $data = $this->all(["id", "user_id", "post_id", "office_id"]);
            $table = new DatatableHelper($data, $this->params, "id");
            return $table->custom_response(["user_id","post_id","office_id","action"])
                ->addColumn('user_id', function (Staff $model) {
                    if($model->user_id){
                        return $model->user?$model->user->name:"None";
                    }else{
                        return "Available";
                    }
                })
                ->addColumn('post_id', function (Staff $model) {
                    return $model->post?$model->post->designation:"None";
                })
                ->addColumn('office_id', function (Staff $model) {
                    return $model->office?$model->office->address.", ".$model->office->officeDistrict->name:"None";
                })->make(true);
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
        $allPosts = Post::get();
        $posts = [];
        foreach ($allPosts as $d) {
            array_push($posts, ["value" => $d->id, "text" => $d->designation]);
        }

        $allOffices = Office::get();
        $offices = [];
        foreach ($allOffices as $d) {
            array_push($offices, ["value" => $d->id, "text" => $d->address.", ".$d->officeDistrict->name]);
        }

        $users = User::orderBy("name")->get();
        $incharges = [];
        foreach ($users as $d) {
            array_push($incharges, ["value" => $d->id, "text" => $d->name]);
        }

        $params = $this->params;
        $title = "New " . $params["singular_title"];
        return view($this->params['dir'] . ".create",
        compact(
            "title",
            "params",
            "offices",
            "posts",
            "incharges"
        ));
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

        return redirect(route($this->params['route'] . ".index"));
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
        return view($this->params['dir'] . ".show", compact("row", "parm", "params"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allPosts = Post::get();
        $posts = [];
        foreach ($allPosts as $d) {
            array_push($posts, ["value" => $d->id, "text" => $d->designation]);
        }

        $allOffices = Office::get();
        $offices = [];
        foreach ($allOffices as $d) {
            array_push($offices, ["value" => $d->id, "text" => $d->address.", ".$d->officeDistrict->name]);
        }

        $users = User::orderBy("name")->get();
        $incharges = [];
        foreach ($users as $d) {
            array_push($incharges, ["value" => $d->id, "text" => $d->name]);
        }

        $row = $this->find($id);
        $parm[$this->params['model']] = $id;
        $title = "Edit " . $this->params['singular_title'];
        $params = $this->params;
        return view($this->params['dir'] . ".edit",
        compact(
            "row",
            "title",
            "params",
            "parm",
            "offices",
            "posts",
            "incharges"
        ));
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
        $validated = $request->validate($this->params['edit_rules']);

        $this->onHandleOperation($request, $id);

        return redirect(route($this->params['route'] . ".index"));
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

        $row->delete();
        if ($request->ajax()) {

            return Response()->json(["status" => "ok", "message" => "Delete Successfully"]);
        }

        return redirect(route($this->params['dir'] . ".index"));
    }
}