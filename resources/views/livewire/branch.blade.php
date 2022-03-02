<div style="background-color: white;">
    <!-- Content Row -->
    <div class="row">
        <div wire:ignore.self class="modal fade" id="verifyRejectModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyRejectModalLabel">Reject Reading</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" wire:model="readingId">
                        <div class="form-group">
                            <select wire:model="rejectCode" class="form-control">
                                <option value="">Select Reject Code</option>
                                @foreach ($rejectCodes as $rejectCode)
                                    <option value="{{ $rejectCode->id }}">{{ $rejectCode->description }}
                                        ({{ $rejectCode->code_number }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Reason</label>
                            <input type="text" class="form-control" wire:model="rejectReason">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancel</button>
                        <button type="button" wire:click="rejectReading()"
                                class="btn btn-danger close-modal">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="viewImageModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewImageModalLabel">Meter Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img style="height: 500px; width: 500px" class="img-fluid img-thumbnail"
                             src="data:image/gif;base64,{{ $imageData }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-body">
            <div class="tab">
                @foreach ($branches as $branch)
                    @if (auth()->user()->type == 2)
                        <button class="{{ $branch->code == $branchCode ? 'active' : '' }}"
                                wire:click="branchCode({{ $branch->code }}, '{{ $branch->name }}')">{{ $branch->name }}</button>
                    @endif
                    @if (auth()->user()->branch_id == $branch->id)
                        <button class="{{ $branch->code == $branchCode ? 'active' : '' }}"
                                wire:click="branchCode({{ $branch->code }}, '{{ $branch->name }}')">{{ $branch->name }}</button>
                    @endif

                @endforeach
            </div>
        </div>
    </div>
    <div wire:model="branchCode">
        <div class="float-container">
            <h3 class="mx-3 my-3" style="float:left; width:65%">{{ $branchName }}</h3>
            <button wire:click="$set('pair', true)" class="btn btn-smrs my-3" style=" width:130px; display:inline-block;">Pairing Meter</button>
            <button wire:click="$set('pair', false)" class="btn btn-smrs my-3" style=" width:200px; display:inline-block;">Non-Pairing Meter</button>
        </div>
        @if($pair)
            <div id="pairMeter">
                <form id="selectForm" method="POST" action="{{ route('updateApproval') }}">
                @csrf
                <!--<button class="btn btn-smrs my-3  //!$bulkSelected ? 'disabled' : '' ">Approved Selected</button>
            <button class="btn btn-smrs my-3"  wire:click="approveSelected">Approved Selected</button>
            <button class="btn btn-danger my-3 //!$bulkSelected ? 'disabled' : '' ">Reject Selected</button>-->
                    <button style="margin-left: 10px;" class="btn btn-smrs my-3" type="submit" name="approve">Approved Selected</button>
                    <table class="table table-striped table-responsive" id="table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Customer Number</th>
                            <th>Book Number</th>
                            <th>Customer Name</th>
                            <th>CITYGATE</th>
                            <th>Meter Number</th>
                            <th>Meter Type</th>
                            <th>Trans UM</th>
                            <th>Reading Status</th>
                            <th>Previous Meter Reading</th>
                            <th>Previous Reading Date</th>
                            <th>Current Meter Reading</th>
                            <th>Current Reading Date</th>
                            <th>Actual Consumption </th>
                            <th>Billable Con. in SM3</th>
                            <th>Adjustment Con. (sm3)</th>
                            <th>Corrected Volume</th>
                            <th>Z Factor</th>
                            <th>Contractual CF</th>
                            <th>Actual CF of the Month <br> {{now()->format('M')}} </th>
                            <th colspan="3">Actual CF for Last 3 Months<br>June | July | August</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($readingsPair as $reading)
                            @if ($reading->meter->consumer->area_code == $branchCode)
                                @if ((auth()->user()->type == 3 && $reading->accepted1 == 0 && $reading->accepted2 == 0)
                                 || (auth()->user()->type == 9 && $reading->accepted1 == 0)
                                 || (auth()->user()->type == 2 && $reading->accepted2 == 0 )
                                 || (auth()->user()->type == 6 && $reading->accepted1 == 0))
                                    <tr style="font-size: smaller;">
                                        <td>
                                            <input type="checkbox" name="reading[]" value="{{ $reading->id }}">
                                        </td>
                                        <td>{{ $reading->meter->consumer->consumer_number }}</td>
                                        <td>Book_Number</td> {{--//put book_number here--}}
                                        <td>{{ $reading->meter->consumer->consumer_name }}</td>
                                        <td>{{ $reading->meter->consumer->city_gate }}</td>

                                        <td>
                                            <a href="home/viewtrend/{{ $reading->meter_id }}"
                                               class="" data-toggle="tooltip"
                                               data-placement="top">{{ $reading->meter->meter_number  }}</a>
                                        </td>
                                        <td>{{ $reading->meter->meter_type }}</td>
                                        <td>TRANSUM</td> {{--//put trans_um here--}}
                                        <td>{{ $reading->meter->last_reading_status }}</td>
                                        <td>{{ $reading->meter->prev_reading }}</td>
                                        <td>{{ $reading->meter->prev_reading_date }}</td>
                                        <td>{{ $reading->meter->last_reading }}</td>
                                        <td>{{ $reading->meter->last_reading_date }}</td>
                                        <td>{{ $reading->actual_consumption }}</td> {{--Actual Consumption--}}
                                        <td>{{ $reading->billable }}</td> {{--Billable Con. in SM3--}}
                                        <td>123</td> {{--Adjustment Con. (sm3)--}}
                                        <td>{{ $reading->corrected_volume }}</td> {{--Corrected Volume--}}
                                        <td>{{ $reading->meter->z_factor }}</td> {{--Z-Factor--}}
                                        <td>123</td> {{--Contractual CF--}}
                                        <td>123</td> {{--Actual CF--}}
                                        <td>123123</td> {{--Actual CF for Last 3 Months June--}}
                                        <td>123231</td> {{--Actual CF for Last 3 Months July--}}
                                        <td>123321</td> {{--Actual CF for Last 3 Months August--}}

                                        <td align="center">
                                            @if ($reading->image)
                                                <a wire:click="showImageModal({{ $reading->id }})">
                                                    <img id="{{ $reading->name }}myImg" style="height: 100px; width: 100px"
                                                         src="data:image/gif;base64,{{ $reading->image }}">
                                                </a>
                                            @else
                                                No Image Available
                                            @endif
                                        </td>

                                        @if (auth()->user()->type == 3 || auth()->user()->type == 6 || auth()->user()->type == 9)
                                            @if ($reading->accepted1 == 0)
                                                <td wire:click="$refresh">
                                                    <button class="btn btn-smrs btn-block btn-sm" type="button"
                                                            wire:click="verify({{ $reading->id }}, false)">Verify </button>

                                                    <button class="btn btn-danger btn-block btn-sm" type="button"
                                                            wire:click="rejectSV({{ $reading->id }})">Reject</button>
                                                </td>
                                            @elseif($reading->accepted1 == 1)
                                                <td>
                                                    <span class="badge badge-pill badge-danger">REJECTED</span>
                                                    <br> <strong> {{ $reading->reason1 }} </strong>
                                                </td>
                                            @elseif($reading->accepted1 == 2 && $reading->accepted2 == 0)
                                                <td style="vertical-align:middle">
                                                    <input class="btn btn-success btn-sm btn-block" type="button"
                                                           wire:click="revokeSV({{ $reading->id }})" value="Revoke">
                                                </td>
                                            @else
                                                <td style="vertical-align:middle">
                                                    <span class="badge badge-pill badge-success">Reading Accpeted</span>
                                                </td>
                                            @endif
                                        @else
                                            @if ($reading->accepted2 == 0)
                                                @if ($reading->accepted1 == 2)
                                                    <td wire:click="$refresh">
                                                        <button class="btn btn-smrs btn-block btn-sm" type="button"
                                                                wire:click="verify({{ $reading->id }}, true)">Verify </button>

                                                        <button class="btn btn-danger btn-block btn-sm" type="button"
                                                                wire:click="rejectSU({{ $reading->id }})">Reject </button>
                                                    </td>
                                                @elseif($reading->accepted1 == 1)
                                                    <td>Reading Rejected By Supervisor<br>Reason: {{ $reading->reason1 }}
                                                    </td>
                                                @else
                                                    <td>Waiting for the Supervisor to Approve</td>
                                                @endif
                                            @elseif($reading->accepted2 == 1)
                                                <td>
                                                    <span class="badge badge-pill badge-danger">REJECTED</span>
                                                    <br><strong>{{ $reading->reason2 }}</strong>
                                                </td>
                                            @elseif($reading->accepted2 == 2)
                                                <td style="vertical-align:middle">
                                                    <input class="btn btn-success btn-sm btn-block" type="button"
                                                           wire:click="revokeSU({{ $reading->id }})" value="Revoke">
                                                </td>
                                            @endif
                                            {{-- <td>Only Supervisor Can Change This Reading</td> --}}
                                        @endif
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <ul id="menu" class="container__menu container__menu--hidden"></ul>
                </form>
            </div>
        @elseif($pair==false)
            <div id="nonPairingMeter">
                <form id="selectForm" method="POST" action="{{ route('updateApproval') }}">
                @csrf
                    <button style="margin-left: 10px;" class="btn btn-smrs my-3" type="submit" name="approve">Approved Selected</button>
                    <table class="table table-striped table-responsive" id="table">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Customer Number</th>
                            <th>Book Number</th>
                            <th>Customer Name</th>
                            <th>CITYGATE</th>
                            <th>Meter Number</th>
                            <th>Meter Type</th>
                            <th>Trans UM</th>
                            <th>Reading Status</th>
                            <th>Previous Meter Reading</th>
                            <th>Previous Reading Date</th>
                            <th>Current Meter Reading</th>
                            <th>Current Reading Date</th>
                            <th>Actual Consumption </th>
                            <th>Billable Con. in SM3</th>
                            <th>Adjustment Con. (sm3)</th>
                            <th>Corrected Volume</th>
                            <th>Z Factor</th>
                            <th>Contractual CF</th>
                            <th>Consumption of the Month, Sm3 <br> {{now()->format('M')}} </th>
                            <th>Average 3 Months Consumption, Sm3</th>
                            <th colspan="3">Monthly Volume, Sm3 <br>June | July | August</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($readingsNoPair as $reading)
                            @if ($reading->meter->consumer->area_code == $branchCode)
                                @if ((auth()->user()->type == 3 && $reading->accepted1 == 0 && $reading->accepted2 == 0)
                                 || (auth()->user()->type == 9 && $reading->accepted1 == 0)
                                 || (auth()->user()->type == 2 && $reading->accepted2 == 0 )
                                 || (auth()->user()->type == 6 && $reading->accepted1 == 0))
                                    <tr style="font-size: smaller;">
                                        <td>
                                            <input type="checkbox" name="reading[]" value="{{ $reading->id }}">
                                        </td>
                                        <td>{{ $reading->meter->consumer->consumer_number }}</td>
                                        <td>Book_Number</td> {{--//put book_number here--}}
                                        <td>{{ $reading->meter->consumer->consumer_name }}</td>
                                        <td>{{ $reading->meter->consumer->city_gate }}</td>

                                        <td>
                                            <a href="home/viewtrend/{{ $reading->meter_id }}"
                                               class="" data-toggle="tooltip"
                                               data-placement="top">{{ $reading->meter->meter_number  }}</a>
                                        </td>
                                        <td>{{ $reading->meter->meter_type }}</td>
                                        <td>TRANSUM</td> {{--//put trans_um here--}}
                                        <td>{{ $reading->meter->last_reading_status }}</td>
                                        <td>{{ $reading->meter->prev_reading }}</td>
                                        <td>{{ $reading->meter->prev_reading_date }}</td>
                                        <td>{{ $reading->meter->last_reading }}</td>
                                        <td>{{ $reading->meter->last_reading_date }}</td>
                                        <td>123</td> {{--Actual Consumption--}}
                                        <td>123</td> {{--Billable Con. in SM3--}}
                                        <td>123</td> {{--Adjustment Con. (sm3)--}}
                                        <td>123</td> {{--Corrected Volume--}}
                                        <td>123</td> {{--Z-Factor--}}
                                        <td>123</td> {{--Contractual CF--}}
                                        <td>123</td> {{--Consumption of the Month, Sm3--}}
                                        <td>123</td> {{--Average 3 Months Consumption, Sm3--}}
                                        <td>123123</td> {{--Actual CF for Last 3 Months June--}}
                                        <td>123231</td> {{--Actual CF for Last 3 Months July--}}
                                        <td>123321</td> {{--Actual CF for Last 3 Months August--}}


                                        <td align="center">
                                            @if ($reading->image)
                                                <a wire:click="showImageModal({{ $reading->id }})">
                                                    <img id="{{ $reading->name }}myImg" style="height: 100px; width: 100px"
                                                         src="data:image/gif;base64,{{ $reading->image }}">
                                                </a>
                                            @else
                                                No Image Available
                                            @endif
                                        </td>

                                        @if (auth()->user()->type == 3 || auth()->user()->type == 6 || auth()->user()->type == 9)
                                            @if ($reading->accepted1 == 0)
                                                <td wire:click="$refresh">
                                                    <button class="btn btn-smrs btn-block btn-sm" type="button"
                                                            wire:click="verify({{ $reading->id }}, false)">Verify </button>

                                                    <button class="btn btn-danger btn-block btn-sm" type="button"
                                                            wire:click="rejectSV({{ $reading->id }})">Reject</button>
                                                </td>
                                            @elseif($reading->accepted1 == 1)
                                                <td>
                                                    <span class="badge badge-pill badge-danger">REJECTED</span>
                                                    <br> <strong> {{ $reading->reason1 }} </strong>
                                                </td>
                                            @elseif($reading->accepted1 == 2 && $reading->accepted2 == 0)
                                                <td style="vertical-align:middle">
                                                    <input class="btn btn-success btn-sm btn-block" type="button"
                                                           wire:click="revokeSV({{ $reading->id }})" value="Revoke">
                                                </td>
                                            @else
                                                <td style="vertical-align:middle">
                                                    <span class="badge badge-pill badge-success">Reading Accpeted</span>
                                                </td>
                                            @endif
                                        @else
                                            @if ($reading->accepted2 == 0)
                                                @if ($reading->accepted1 == 2)
                                                    <td wire:click="$refresh">
                                                        <button class="btn btn-smrs btn-block btn-sm" type="button"
                                                                wire:click="verify({{ $reading->id }}, true)">Verify </button>

                                                        <button class="btn btn-danger btn-block btn-sm" type="button"
                                                                wire:click="rejectSU({{ $reading->id }})">Reject </button>
                                                    </td>
                                                @elseif($reading->accepted1 == 1)
                                                    <td>Reading Rejected By Supervisor<br>Reason: {{ $reading->reason1 }}
                                                    </td>
                                                @else
                                                    <td>Waiting for the Supervisor to Approve</td>
                                                @endif
                                            @elseif($reading->accepted2 == 1)
                                                <td>
                                                    <span class="badge badge-pill badge-danger">REJECTED</span>
                                                    <br><strong>{{ $reading->reason2 }}</strong>
                                                </td>
                                            @elseif($reading->accepted2 == 2)
                                                <td style="vertical-align:middle">
                                                    <input class="btn btn-success btn-sm btn-block" type="button"
                                                           wire:click="revokeSU({{ $reading->id }})" value="Revoke">
                                                </td>
                                            @endif
                                            {{-- <td>Only Supervisor Can Change This Reading</td> --}}
                                        @endif
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <ul id="menu" class="container__menu container__menu--hidden"></ul>
                </form>
            </div>
        @endif
    </div>
</div>
@section('script')
    <script>
        $(document).ready(function () {
            $("#selectForm #selectAll").click(function () {
                $("#selectForm input[type='checkbox']").prop('checked', this.checked);
            });
        });

        Livewire.on('rejectEvent', readingId => {
            var modalDialog = $("#verifyRejectModal");
            modalDialog.modal();
        })

        Livewire.on('hideModalEvent', readingId => {
            var modalDialog = $("#verifyRejectModal");
            modalDialog.modal('hide');
        })

        Livewire.on('showImageEvent', readingId => {
            var modalDialog = $("#viewImageModal");
            modalDialog.modal();
        })

        document.addEventListener('DOMContentLoaded', function () {
            const menu = document.getElementById('menu');
            const table = document.getElementById('table');
            const headers = [].slice.call(table.querySelectorAll('th'));
            const cells = [].slice.call(table.querySelectorAll('th, td'));
            const numColumns = headers.length;

            const thead = table.querySelector('thead');
            thead.addEventListener('contextmenu', function (e) {
                e.preventDefault();

                const rect = thead.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                menu.style.top = `${y}px`;
                menu.style.left = `${x}px`;
                menu.classList.toggle('container__menu--hidden');

                document.addEventListener('click', documentClickHandler);
            });

            // Hide the menu when clicking outside of it
            const documentClickHandler = function (e) {
                const isClickedOutside = !menu.contains(e.target);
                if (isClickedOutside) {
                    menu.classList.add('container__menu--hidden');
                    document.removeEventListener('click', documentClickHandler);
                }
            };

            const showColumn = function (index) {
                cells
                    .filter(function (cell) {
                        return cell.getAttribute('data-column-index') === `${index}`;
                    })
                    .forEach(function (cell) {
                        cell.style.display = '';
                        cell.setAttribute('data-shown', 'true');
                    });

                menu.querySelectorAll(`[type="checkbox"][disabled]`).forEach(function (checkbox) {
                    checkbox.removeAttribute('disabled');
                });
            };

            const hideColumn = function (index) {
                cells
                    .filter(function (cell) {
                        return cell.getAttribute('data-column-index') === `${index}`;
                    })
                    .forEach(function (cell) {
                        cell.style.display = 'none';
                        cell.setAttribute('data-shown', 'false');
                    });
                // How many columns are hidden
                const numHiddenCols = headers.filter(function (th) {
                    return th.getAttribute('data-shown') === 'false';
                }).length;
                if (numHiddenCols === numColumns - 1) {
                    // There's only one column which isn't hidden yet
                    // We don't allow user to hide it
                    const shownColumnIndex = thead
                        .querySelector('[data-shown="true"]')
                        .getAttribute('data-column-index');

                    const checkbox = menu.querySelector(
                        `[type="checkbox"][data-column-index="${shownColumnIndex}"]`
                    );
                    checkbox.setAttribute('disabled', 'true');
                }
            };

            cells.forEach(function (cell, index) {
                cell.setAttribute('data-column-index', index % numColumns);
                cell.setAttribute('data-shown', 'true');
            });

            headers.forEach(function (th, index) {
                // Build the menu item
                const li = document.createElement('li');
                const label = document.createElement('label');
                const checkbox = document.createElement('input');
                checkbox.setAttribute('type', 'checkbox');
                checkbox.setAttribute('checked', 'true');
                checkbox.setAttribute('data-column-index', index);
                checkbox.style.marginRight = '.25rem';

                const text = document.createTextNode(th.textContent);

                label.appendChild(checkbox);
                label.appendChild(text);
                label.style.display = 'flex';
                label.style.alignItems = 'center';
                li.appendChild(label);
                menu.appendChild(li);

                // Handle the event
                checkbox.addEventListener('change', function (e) {
                    e.target.checked ? showColumn(index) : hideColumn(index);
                    menu.classList.add('container__menu--hidden');
                });
            });
        });

    </script>
@endsection
