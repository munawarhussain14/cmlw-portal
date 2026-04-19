<?php

namespace App\Helper;

use DataTables;
use Auth;

class DatatableHelper
{

    private $data, $params;
    private $actionButtons;
    private $custom_btn = [];
    private $btn = "";
    private $primaryKey;

    function __construct($data, $params, $primaryKey = "id")
    {
        $this->data = $data;
        $this->params = $params;
        $this->primaryKey = $primaryKey;
    }

    public function table_response()
    {
        return Datatables::of($this->data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return $this->action($row);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function custom_response($rawColumns = ['action'])
    {
        return Datatables::of($this->data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return $this->action($row);
            })
            ->rawColumns($rawColumns);
    }

    function action($row)
    {
        $specifier = $this->params["model"];
        $parm[$specifier] = $row[$this->primaryKey];
        $this->btn = "";
        if (Auth::user()->can("read-" . $this->params["module_name"])) {
            foreach ($this->custom_btn as $button) {
                $button['url'] = str_replace("{id}", $row[$this->primaryKey], $button['url']);
                $this->btn .= "<a href='" . $button['url'] . "' class='action-btn btn btn-primary btn-sm'><i class='" . $button['icon'] . "'></i> " . $button['title'] . "</a>&nbsp;";
            }
        }

        if (Auth::user()->can("read-" . $this->params["module_name"])) {
            $this->btn .= "<a href='" . route($this->params['route'] . ".show", $parm) . "' class='action-btn btn btn-info btn-sm'><i class='fas fa-search'></i> View</a>&nbsp;";
        }

        if (Auth::user()->can("update-" . $this->params["module_name"])) {
            $this->btn .= "<a href='" . route($this->params['route'] . ".edit", $parm) . "' class='action-btn btn btn-primary btn-sm'><i class='fas fa-pencil-alt'></i> Edit</a>&nbsp;";
        }

        if (Auth::user()->can("delete-" . $this->params["module_name"])) {
            $this->btn .= "<a href='javascript:void(0)' onClick='onRemove(" . $row[$this->primaryKey] . ")' class='action-btn delete btn btn-danger btn-sm'><i class='fa fa-trash'></i> Delete</a>";
        }
        return $this->btn;
    }

    function appendActionButton($url, $title, $icon)
    {
        array_push($this->custom_btn, ["url" => $url, "title" => $title, "icon" => $icon]);
        //$this->btn .= "<a href='$url' class='action-btn btn btn-primary btn-sm'><i class='$icon'></i> $title</a>&nbsp;";
    }
}
