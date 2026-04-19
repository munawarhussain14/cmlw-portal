<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $params = [
        "singular_title"=>"Setting",
        "plural_title"=>"Settings",
        "upload_dir"=>"restaurant",
        "create_rules"=>[
            "site_title"=>"required",
            "logo"=>"required|mimes:png,jpg,jpeg|max:2048",
            "pdf_menu"=>"required|mimes:pdf",
            "address"=>"required",
            "primary_color"=>"required",
            "secondary_color"=>"required"
        ],
        "edit_rules"=>[
            "name"=>"required",
            "logo"=>"mimes:png,jpg,jpeg|max:2048",
            "pdf_menu"=>"mimes:pdf",
            "address"=>"required",
            "primary_color"=>"required",
            "secondary_color"=>"required"
        ]
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        //
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
