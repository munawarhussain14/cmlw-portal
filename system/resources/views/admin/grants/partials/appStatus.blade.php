<div class="accordion" id="appStatusContainer">
    <form id="status-form" action="{{ route('admin.change.app.status') }}" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="id" value="{{ $data->id }}" />
        <div class="card card-secondary">


            <div class="card-header p-0" id="appStatus">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                    data-target="#appStatusBody" aria-expanded="true" aria-controls="collapseOne">
                    <h3 class="card-title">Change Category of Application</h3>
                    <div class="card-header-actions">
                        Last Action Taken By:
                        {{ $data->doc_verfied ? $data->doc_verfied->short_desg . ' - ' . $data->doc_verfied->name : 'None' }}{{ ', Updated at : ' . date('d-m-Y h:m:s a', strtotime($data->updated_at)) }}
                    </div>
                </button>
            </div>
            <div class="collapse @error('category') show @enderror" id="appStatusBody" aria-labelledby="appStatus"
                data-parent="#appStatusContainer">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.layouts.partials.form.select', [
                                'name' => 'category',
                                'label' => 'Application Status',
                                'value' => $data->category,
                                'id' => 'category',
                                'required' => true,
                                'options' => [
                                    ['value' => 'General', 'text' => 'General Scholarship'],
                                    ['value' => 'Engineering', 'text' => 'Engineering Quality Education'],
                                    ['value' => 'Medical', 'text' => 'Medical Quality Education'],
                                    ['value' => 'Special', 'text' => 'Specail Education'],
                                    ['value' => 'Top', 'text' => 'Top 50'],
                                ],
                            ])
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary pull-right">Change Status</button>
                </div>
            </div>
        </div>
    </form>
</div>
