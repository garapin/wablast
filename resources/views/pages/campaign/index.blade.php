<x-layout-dashboard title="Campaigns">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @if (session()->has('alert'))
        <x-alert>
            @slot('type', session('alert')['type'])
            @slot('msg', session('alert')['msg'])
        </x-alert>
    @endif
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Campaign</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">History</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <button onclick="clearCampaign()" type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                data-bs-target="#deleteAllModal">
                <i class="bi bi-trash-fill"></i> Clear Campaign
        </div>
    </div>
    {{-- end breadcrumb --}}

    {{-- table --}}
    <div class="row">
        <div class="col-12 col-lg-9 d-flex">
            <div class="card w-100">
                <div class="card-header py-3">
                    <div class="row g-3">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Device</th>
                                    <th>Name</th>
                                    <th>Message</th>
                                    <th>Schedule</th>
                                    <th>Summary</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($campaigns->total() == 0)
                                    <x-no-data colspan="6" text="No Autoreplies added yet" text="No Campaigns added yet" />
                                @endif
                                @foreach ($campaigns as $campaign)
                                    <tr>
                                        <td>{{ $campaign->device->body }}</td>
                                        <td>{{ $campaign->name }}</td>
                                        <td>
                                            <a onclick="viewMessage('{{ $campaign->id }}')" href="#"
                                                class="text-info" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Views Message"><i class="bi bi-eye-fill"></i>
                                                {{ $campaign->type }}</a>
                                        </td>
                                        <td>{{ $campaign->schedule }}</td>
                                        <td>
                                            {{ $campaign->blasts_count }} <span class="badge bg-primary">total</span>
                                            <br>
                                            {{ $campaign->blasts_success }} <span
                                                class="badge bg-success">Success</span>
                                            <br>
                                            {{ $campaign->blasts_failed }} <span class="badge bg-danger">Failed</span>
                                            <br>
                                            {{ $campaign->blasts_pending }} <span
                                                class="badge bg-warning">Waiting</span>
                                            {{-- button view blasts list --}}
                                            <br>
                                        </td>
                                        <td>
                                            @if ($campaign->status == 'completed')
                                                <span class="badge rounded-pill bg-success">Completed</span>
                                            @elseif ($campaign->status == 'paused')
                                                <span class="badge rounded-pill bg-secondary">Paused</span>
                                            @elseif ($campaign->status == 'waiting')
                                                <span class="badge rounded-pill bg-warning">Waiting</span>
                                            @elseif ($campaign->status == 'processing')
                                                <span class="badge rounded-pill bg-info">Processing</span>

                                            @else
                                                <span
                                                    class="badge rounded-pill bg-danger">{{ $campaign->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3 fs-6">
                                                 <a href="{{route('campaign.blasts', $campaign->id)}}"
                                                    class="text-primary" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title="View Data">
                                                <i class="bi bi-eye-fill"></i>
                                                </a>
                                                @if ($campaign->status == 'processing' || $campaign->status == 'waiting')
                                                    <a href="#" onclick="pauseCampaign('{{ $campaign->id }}')"
                                                        class="text-warning" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" title="Pause"><i
                                                            class="bi bi-pause-fill"></i></a>
                                                @endif
                                                @if ($campaign->status == 'paused')
                                                    <a href="#" onclick="resumeCampaign('{{ $campaign->id }}')"
                                                        class="text-success" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom" title="Resume"><i
                                                            class="bi bi-play-fill"></i></a>
                                                @endif
                                                <a href="#" onclick="deleteCampaign('{{ $campaign->id }}')"
                                                    class="text-danger" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom" title="Delete"><i
                                                        class="bi bi-trash-fill"></i></a>

                                            </div>


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item {{ $campaigns->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $campaigns->previousPageUrl() }}">Previous</a>
                            </li>

                            @for ($i = 1; $i <= $campaigns->lastPage(); $i++)
                                <li class="page-item {{ $campaigns->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $campaigns->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <li
                                class="page-item {{ $campaigns->currentPage() == $campaigns->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $campaigns->nextPageUrl() }}">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 d-flex">
            <div class="card w-100">
                <div class="card-header py-3">
                    <h5 class="mb-0">Filter by</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Device</label>
                            <input
                            value="{{ request()->has('device') ? request()->device : '' }}"
                             type="number" name="device" class="form-control" placeholder="Order ID">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select
                        
                            name="status" class="form-select">
                                <option {{ request()->has('status') && request()->status == 'all' ? 'selected' : '' }} value="all">All</option>
                                <option {{ request()->has('status') && request()->status == 'completed' ? 'selected' : '' }} value="completed">Completed</option>
                                <option {{ request()->has('status') && request()->status == 'processing' ? 'selected' : '' }} value="processing">Processing</option>
                                <option {{ request()->has('status') && request()->status == 'waiting' ? 'selected' : '' }} value="waiting">Waiting</option>
                                <option {{ request()->has('status') && request()->status == 'paused' ? 'selected' : '' }} value="paused">Paused</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="d-grid">
                                <button class="btn btn-primary">Filter Campaign</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- end table --}}

    {{-- Modal preview message --}}
    <div class="modal fade" id="previewMessage" tabindex="-1" aria-labelledby="previewMessage" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Campaign Message Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body preview-message-area">
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal Preview Message --}}
</x-layout-dashboard>
<script>
    function viewMessage(id) {
        $.ajax({
            url: `/preview-message`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: {
                id: id,
                table: 'campaigns',
                column: 'message'
            },
            dataType: 'html',
            success: (result) => {

                $('.preview-message-area').html(result);
                $('#previewMessage').modal('show')
            },
            error: (error) => {
                console.log(error);
                toastr['error']('something went wrong')
            }
        })
        // 
    }

    function pauseCampaign(id) {
        $.ajax({
            url: `/campaign/pause/${id}`,
            type: 'POST',
            dataType: 'json',
            success: (result) => {
                location.reload();
            },
            error: (error) => {
                toastr['error']('something went wrong when pausing campaign')
            }
        })
    }

    function resumeCampaign(id) {
        $.ajax({
            url: `/campaign/resume/${id}`,
            type: 'POST',
            dataType: 'json',
            success: (result) => {
                location.reload();
            },
            error: (error) => {
                toastr['error']('something went wrong when resuming campaign')
            }
        })
    }

    function deleteCampaign(id) {
        if (!confirm('Are you sure you want to delete this campaign?')) {
            toastr['error']('Cancel deleting campaign')
            return;
        }
        $.ajax({
            url: `/campaign/delete/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'DELETE',
            dataType: 'json',
            success: (result) => {
                location.reload();
            },
            error: (error) => {
                toastr['error']('something went wrong when deleting campaign ')
            }
        })
    }

    function clearCampaign(id) {
        if (!confirm('Are you sure you want to clear this campaign?')) {
            toastr['error']('Cancel clearing campaign')
            return;
        }
        $.ajax({
            url: `/campaign/clear`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'DELETE',
            dataType: 'json',
            success: (result) => {
                location.reload();
            },
            error: (error) => {
                toastr['error']('something went wrong when clearing campaign ')
            }
        })
    }
</script>
