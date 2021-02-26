<?php 
use App\Libraries\Common;
?>
<html>

<body style="width:200px; padding: 10px;">
    <center>
        <h3>Cheetah Group</h3>
    </center>

    <p>Installment Receipt</p>
    <p>Tel: 011-1111111111</p>
    <p>Billed at: {{$payment->created_at}}</p>
    <center> <p>----------------------------------</p> </center>
    <p>Customer: {{$payment->loan->customer->full_name}}</p>
    <p>Loan No: #{{$payment->loan->id}}</p>
    <p>Loan Amount: {{$payment->loan->loan_amount}}</p>
    <p>Rental: Rs. {{$payment->loan->daily_rental}}</p>
    <p>Remaining: {{$payment->loan->remaining_days}}/{{$payment->loan->installments}}</p>
    <p>Start Date: {{$payment->loan->start_date->format('Y-m-d')}}</p>
    <p>End Date: {{$payment->loan->start_date->addDays($payment->loan->installments)->format('Y-m-d')}}</p>
    <center> <p>----------------------------------</p> </center>
    <p>Total Paid: Rs. {{Common::getInCurrencyFormat($payment->amount)}}</p>
    <p>Total Due: Rs. {{$payment->loan->loan_amount - $totalPaid}}</p>
    <p>Paid Today: Rs. {{$paidToday}}</p>
    <p>Arrears: Rs. {{$arrears}}</p>
    <center> <p>----------------------------------</p> </center>
</body>

</html>