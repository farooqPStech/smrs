<div>

    <div class="row">
        <div class="card card-body">
            <div class="tab">

                <button class="tablinks" wire:click="consumerType(11)" id="defaultOpen">Industrial</button>
                <button class="tablinks" wire:click="consumerType(1)">Residential</button>
            </div>
            <p>{{ $consumerType }}</p>
            <div>
                <h3>Gas Malaysia Distribution SDN BHD</h3>
                <div class="tab">
                    @foreach ($branches as $branch)
                        <button wire:click="branchCode({{ $branch->code }})">{{ $branch->name }}</button>
                    @endforeach

                </div>
                <div>
                    {{ $branchCode }}
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
                                <th>CF Variant 1</th>
                                <th>CF Variant 2</th>
                                <th>CF Variant 3</th>
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
                            @foreach ($readings as $reading)
                                @if ($reading->meter->consumer->area_code == $branchCode)
                                    <tr>
                                        <th scope="row">{{ $reading->id }}</th>
                                        <td>{{ $reading->meter->consumer->consumer_number }}</td>
                                        <td>{{ $reading->meter->meter_number }}</td>
                                        <td>{{ $reading->meter->consumer->consumer_name }}</td>
                                        <td>{{ $reading->meter->consumer->area_code }}</td>
                                        <td>{{ $reading->meter->meterLocation->description }}</td>
                                        <td>{{ $reading->meter->meter_type }}</td>
                                        <td>{{ $reading->obstacleCode->description }}</td>
                                        <td>{{ $reading->reading }}</td>
                                        @if ($reading->meter->z_factor == 0)
                                            <td>0</td>
                                        @elseif ($reading->meter->cf_factor / $reading->meter->z_factor > 5 ||
                                            $reading->meter->cf_factor / $reading->meter->z_factor < -5) <td
                                                style="color:white; background-color: red;">
                                                {{ number_format($reading->meter->cf_factor / $reading->meter->z_factor) }}
                                                </td>
                                            @else
                                                <td>{{ number_format($reading->meter->cf_factor / $reading->meter->z_factor) }}
                                                </td>
                                        @endif
                                        @php
                                            $reading->meter;
                                        @endphp
                                        <td>{{ $reading->id }}</td>
                                        <td>{{ $reading->id }}</td>
                                        <td>{{ $reading->id }}</td>
                                        <td>{{ $reading->id }}</td>
                                        <td>{{ $reading->id }}</td>
                                        <td>{{ number_format($reading->meter->daily_average_consumption * 90) }}</td>
                                        <td>{{ $reading->mmbtu }}</td>
                                        @if ($reading->image === null)
                                            <td> <img src={{ asset('img/default.jpg') }} width="50px" alt=""> </td>
                                        @else
                                            <td> <img src="{{ $reading->image }}" width="50px" alt=""> </td>
                                        @endif



                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
