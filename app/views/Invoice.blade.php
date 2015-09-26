<!--Created by PhpStorm.-->
<!--User: rikazdev-->
<!--Date: 3/8/15-->
<!--Time: 6:05 AM-->

<!doctype html>
<html>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
        }

        th {
            text-align: left;
        }
    </style>
</head>
</head>

<body>

<div style="margin-left: 50px; margin-right: 50px">
    <!-- <div align="center" class="text-warning"><h1> US Invoice </h1></div>
     <br/>-->
    <br/>
    <br/>

    <div style="width: 100%; display: table;">
        <div style="display: table-row">
            <div style="width: 600px; display: table-cell;">
                <br/>
                <!--   <img src="../img/logo.png">-->
                <img src="http://128.199.124.27/resources/logo.png" height="100" width="290">

                <p>UNIQUE INDEED CUSTOMERS DELIGHT</p>
            </div>
            <div style="display: table-cell;">
                <div style="width:100%">
                    240, Gall Road. Colombo - 04 <br>
                    T : 0112505570 <br>
                    M : 0777987987 <br>
                    <br>

                    E : info@uniquestore.lk <br>
                    Website : www.uniquestore.lk
                </div>
            </div>
        </div>
    </div>

    <br/>

    <div align="center" class="text-warning"><h1> Invoice </h1></div>
    <br/>

    <div style="width: 100%; display: table;">
        <div style="display: table-row">
            <div style="width: 600px; display: table-cell;">

                <table style="width:60%" border="1">
                    <b>Customer</b>
                    <td style="text-align: 10px">{{ $customer->first_name . ' ' .$customer->last_name }}<br/>
                        {{ $customer->phone}} <br/>
                        {{ $customer->email}}
                    </td>
                </table>
            </div>
            <div style="display: table-cell;">

                <table style="width:100%" border="1">
                    <br/>
                    <td>{{ $invoice->invoice_id }} <br/>
                        <b>Date</b> <br/>
                        {{ $invoice->created_at }}
                    </td>
                </table>

            </div>
        </div>
    </div>
    <br/>

    <div style="width: 100%;">

        <table border="1" style="width:100%">
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Description</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            @foreach ($invoice_details as $detais)
            <tr>
                <td>
                    {{$detais->title}}
                </td>
                <td>
                    {{$detais->quantity}}
                </td>

                <td>  {{$detais->description}} <br>

                    @if ($detais->category_id == 16)
                    <label>IMIE NO: {{$detais->imei}}</label>
                    @endif
                    <br>
                    @if ($detais->purchase_imei)
                    Exchange<br>
                    <label>Purchase IMIE NO: {{$detais->purchase_imei}}</label>
                    @endif



                </td>

                <td>
                    {{$detais->unit_price}}
                </td>
                <td>
                    {{$detais->quantity * $detais->unit_price }}
                </td>
            </tr>
            @endforeach

            <tr><b>
                <td colspan="3">

                </td>


                <td>
                    Total
                </td>
                <td>
                    {{ $invoice->total }}
                </td>
                </b>
            </tr>
            <tr><b>
                    <td colspan="3">

                    </td>
                    <td>
                        Paid
                    </td>
                    <td>
                        {{ $invoice->paid }}
                    </td>
                </b>
            </tr>
            <tr><b>
                    <td colspan="3">

                    </td>


                    <td>
                        Balance
                    </td>
                    <td>
                        {{ $invoice->balance}}
                    </td>
                </b>
            </tr>
        </table>


    </div>

    <br/>

    <div style="width: 100%; display: table;">
        <div style="display: table-row">
            <div style="width: 600px; display: table-cell;">
                <!--<table style="width:60%;height: 100px"
                " border="1">
                <td>
                    <b>Note</b>

                </td>
                </table>-->

            </div>
            <div style="display: table-cell;">
              <!--  <table style="width:100%;height: 100px" border="1">
                    <td>
                        <b>Total</b>
                        <br>
                        <br>
                        {{ $invoice->total }} <br/>
                    </td>
                </table>-->

            </div>
        </div>
    </div>

    <br/>

    <div style="width: 100%; display: table;">
        <div style="display: table-row">
            <div style="width: 600px; display: table-cell;">
                One year Warranty covers for software Six months Warranty for all accessories  but defects due to other causes such as negligence,misuse,
                improper operation, power fluctuation,lightning or
                other natural disasters are not included under this warranty. Warranty for LCD Ribbon is not available. We are not responsible for the
                refund of money or phone  to phone replacement.
                Warranty becomes void when you update the System Software by yourself.
                Repairs or Replacements necessitated by such causes under this Warranty are subject to charge for labour,time and material.
            </div>
       <!--     <div style="display: table-cell;">
                <table style="width:100%;height: 100px" border="1">
                    <td>
                        <b>Total</b>
                        <br>
                        <br>
                        {{ $invoice->total }} <br/>
                    </td>
                </table>

            </div>
        </div>-->
    </div>

        <br>
        <div style="width: 100%; display: table;">
            <div style="display: table-row">
                <div style="width: 600px; display: table-cell;">
                    <table style="width:60%;height: 100px"
                    " border="1">
                    <td>
                        <h5><b>Received By</b></h5>

                    </td>
                    </table>

                </div>
                <div style="display: table-cell;">
                      <table style="width:100%;height: 100px" border="1">
                          <td>
                              <h5><b>For unique store</b></h5>
                      </table>

                </div>
            </div>
        </div>

</div>

</body>
</html>
