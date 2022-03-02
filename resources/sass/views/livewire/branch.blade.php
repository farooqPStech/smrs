<div style="background-color: white;">

    <!-- Content Row -->

    <div class="row">
        <div class="card card-body">
            <div class="tab">
                @foreach ($branches as $branch)
                    <button wire:click="branchCode({{ $branch->code }})">{{ $branch->name }}</button>
                @endforeach
            </div>
        </div>
    </div>
    <div wire:model="branchCode">
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Account Number</th>
                    <th>Meter Number</th>
                    <th>Consumer Name</th>
                    <th>Area</th>
                    <th>Meter Location</th>
                    <th>Meter Type</th>
                    <th>Obstacle Code</th>
                    <th>Reading (sm3)</th>
                    <th>Actual CF</th>
                    <th>Contractual CF</th>
                    <th>Current CF Variant</th>
                    <th>Average 3 Month Consumption</th>
                    <th>MMBTU (sm3)</th>
                    <th>Issue Code</th>
                    <th>Image</th>
                    <th>Trend</th>
                    <th>Supervisor</th>
                    <th>Superuser / Final Approver</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                @foreach ($readings as $reading)
                    <?php if($i <= 15){ ?>
                    @if ($reading->meter->consumer->area_code == $branchCode)

                        @if ($reading->accepted1 == 2)
                            <tr style="font-size: smaller;">
                                <td>{{ $reading->id }}</td>
                                <td>{{ $reading->meter->consumer->consumer_number }}</td>
                                <td>{{ $reading->meter->meter_number }}</td>
                                <td>{{ $reading->meter->consumer->consumer_name }}</td>
                                <td>{{ $reading->meter->consumer->area_code }}</td>
                                <td>{{ $reading->meter->meterLocation->description }}</td>
                                <td>{{ $reading->meter->meter_type }}</td>
                                <td>{{ $reading->obstacleCode->description }}</td>
                                <td>{{ $reading->reading }}</td>
                                @if ($reading->meter->z_factor == 0)
                                    <td> 0 </td>
                                @else
                                    <td>{{ $reading->meter->cf_factor / $reading->meter->z_factor }}</td>
                                @endif

                                <?php

                                $records = count($reading->meter->readings);
                                if (!$records == 0) {
                                    $rec1 = $records - 1;
                                    $cf1 = $reading->meter->readings[$rec1]->current_consumption;
                                    $cf2 = 0;
                                    $cf3 = 0;
                                } elseif ($records < 3) {
                                    $rec1 = $records - 1;
                                    $rec2 = $records - 2;

                                    $cf1 = $reading->meter->readings[$rec1]->current_consumption;
                                    $cf2 = $reading->meter->readings[$rec2]->current_consumption;
                                    $cf3 = 0;
                                } elseif ($records > 3) {
                                    $rec1 = $records - 1;
                                    $rec2 = $records - 2;
                                    $rec3 = $records - 3;

                                    $cf1 = $reading->meter->readings[$rec1]->current_consumption;
                                    $cf2 = $reading->meter->readings[$rec2]->current_consumption;
                                    $cf3 = $reading->meter->readings[$rec3]->current_consumption;
                                }
                                ?>
                                @if ($cf1 == 0 && $cf2 == 0 && $cf3 == 0)
                                    <td>0</td>
                                    <?php $ConCF = 0;
                                    $actualAverage = 0; ?>
                                @else
                                    <?php $actualAverage = ($cf1 + $cf2 + $cf3) / 3;
                                    $ConCF = ($reading->current_consumption - $actualAverage) / ($actualAverage * 100);
                                    ?>
                                    <td>{{ $ConCF }}</td>
                                @endif
                                @if ($ConCF == 0)
                                    <td>0</td>
                                @else
                                    <?php $cfVarient = ($reading->meter->cf_factor - $ConCF) / $ConCF; ?>
                                    <td>{{ $cfVarient }}</td>
                                @endif
                                <td>{{ $actualAverage }}</td>
                                <td>{{ $reading->mmbtu }}</td>
                                <td>{{ $reading->issueCode->description }}</td>
                                <td>Null</td>
                                <td align="center"><a href="home/viewtrend/{{ $reading->meter_id }}"
                                        class="btn btn-smrs" data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa fa-edit text-white">
                                            View Trend</i></a>
                                </td>
                                @if (auth()->user()->type == 3 || auth()->user()->type == 6)
                                    @if ($reading->accepted1 == 0)
                                        <td>
                                            <input class="fa text-white btn btn-smrs" type="button"
                                                onclick="verify({{ $reading->id }},{{ auth()->user()->type }},{{ auth()->user()->id }})"
                                                value="Verify"> <br><br>
                                            <input class="fa text-white btn btn-danger " type="button"
                                                onclick="reject({{ $reading->id }},{{ auth()->user()->type }},{{ auth()->user()->id }})"
                                                value="Reject">
                                        </td>
                                    @elseif($reading->accepted1 == 1)
                                        <td>Rejected.<br>Reason: {{ $reading->reason2 }}</td>
                                    @elseif($reading->accepted1 == 2)
                                        <td>Accepted <br><br><input class="fa text-white btn btn-danger" type="button"
                                                onclick="revoke({{ $reading->id }},{{ auth()->user()->type }},{{ auth()->user()->id }})"
                                                value="Revoke"> </td>
                                    @endif
                                @else
                                    <td>Only Supervisor Can Change This Reading</td>
                                @endif


                                @if (auth()->user()->type == 2 || auth()->user()->type == 5)
                                    @if ($reading->accepted2 == 0)
                                        @if ($reading->accepted1 == 2)
                                            <td>
                                                <input class="fa text-white btn btn-smrs" type="button"
                                                    onclick="verify({{ $reading->id }},{{ auth()->user()->type }},{{ auth()->user()->id }})"
                                                    value="Verify"> <br><br>
                                                <input class="fa text-white btn btn-danger " type="button"
                                                    onclick="reject({{ $reading->id }},{{ auth()->user()->type }},{{ auth()->user()->id }})"
                                                    value="Reject">
                                            </td>
                                        @elseif($reading->accepted1 == 1)
                                            <td>Reading Rejected By Supervisor<br>Reason: {{ $reading->reason1 }}</td>
                                        @else
                                            <td>Waiting for the Supervisor to Approve</td>
                                        @endif
                                    @elseif($reading->accepted2 == 1)
                                        <td>Rejected.<br>Reason: {{ $reading->reason2 }}</td>
                                    @elseif($reading->accepted2 == 2)
                                        <td>Accepted <br><br><input class="fa text-white btn btn-danger" type="button"
                                                onclick="revoke({{ $reading->id }},{{ auth()->user()->type }},{{ auth()->user()->id }})"
                                                value="Revoke"></td>
                                    @endif
                                @else
                                    <td style="font-size: smaller;">Only Superuser/Final Approver Can Change This
                                        Reading</td>
                                @endif

                            </tr>

                        @endif
                        <?php $i++; ?>
                    @endif
                    <?php } ?>
                @endforeach



            </tbody>
        </table>


    </div>
</div>
