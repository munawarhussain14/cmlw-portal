@extends('admin.layouts.app')

@section("content")
<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
          <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $params['plural_title'] }}</h3>
                @if (isset($params['module_name']))
                    @can('create-' . $params['module_name'])
                        <div class="card-header-actions">
                            <a href="{{ isset($params['custom_create_url']) ? $params['custom_create_url'] : route($params['route'] . '.create') }}"
                                class="card-header-action"><i class="fa fa-plus"></i> Create {{ $params['singular_title'] }}</a>
                        </div>
                    @endcan
                @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="data-table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                                <th>Expenditure Month</th>
                                <th>Pending</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($compilation_months as $month)
                      <tr>
                        <td>
                          @php
                          $date = new \Carbon\Carbon($month->expenditure_month);
                          echo $date->format('F Y');
                          @endphp
                          </td>
                        <td>
                          @php
                          $compilations = \App\Models\Compilation::where("expenditure_month",$month->expenditure_month)->where("status","<>","complete")->get();
                          $offices = [];
                          foreach($compilations as $compilation)
                            {
                              $office = "<span class='badge badge-danger'>".$compilation->office->ddo." ".$compilation->office->officeDistrict->name."</span>";
                              array_push($offices,$office);
                            }
                          @endphp
                          {!!join(" ",$offices)!!}
                          <!-- {{$month->total_offices}} -->
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
          </div>
        </div>
      </div>
</section>
@endsection