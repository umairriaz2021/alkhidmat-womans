@extends('admin.layout')
@section('title','All Transactions')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">All Transactions</h4>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Full Name </th>
                                    <th> Email </th>
                                    <th> Phone </th>
                                    <th>Amount</th>
                                    <th> Status </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions['data'] as $transaction)
                                <tr>
                                    <td>{{ $transaction['first_name']." ".$transaction['last_name'] }}</td>
                                    <td>{{ $transaction['email'] }}</td>
                                    <td>{{$transaction['phone']}}</td>
                                    <td>
                                       <span class="badge
                                                @if($transaction['status'] == 'complete')
                                                    bg-success-subtle text-success
                                                @elseif($transaction['status'] == 'pending')
                                                    bg-warning-subtle text-warning-emphasis
                                                @elseif($transaction['status'] == 'processing')
                                                    bg-info-subtle text-info-emphasis
                                                @else
                                                    bg-secondary-subtle text-secondary
                                                @endif
                                            ">
                                                {{ ucwords($transaction['status']) }}
                                            </span>
                                    </td>
                                    <td>
                                        <a href="">View Details</a> | 
                                        <a href="javascript:void(0)" class="deleteTransaction text-danger" data-id="{{ $transaction['id'] }}">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3"></div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

</script>
@endsection