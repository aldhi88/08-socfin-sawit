<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ env('APP_NAME').$data['title'] }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{ $data['desc'] }}" />
    <meta name="keywords" content="{{ $data['desc'] }}">
    <meta name="author" content="{{ env('APP_AUTHOR') }}" />
    <link rel="icon" href="{{ env('PORTAL_URL').'/images/logo.png' }}" type="image/x-icon">
</head>
<style>
    table.minimalistBlack {
    font-family: Arial, Helvetica, sans-serif;
    width: 100%;
    text-align: left;
    border-collapse: collapse;
    }
    table.minimalistBlack td, table.minimalistBlack th {
    border: 1px solid #acacac;
    padding: 4px 3px;
    }
    table.minimalistBlack thead.thead-title th {
    border: 1px solid rgb(110, 161, 255);
    padding: 4px 3px;
    }
    table.minimalistBlack tbody td {
    font-size: 12px;
    padding-top: 11px;
    padding-bottom: 10px;
    }
    table.minimalistBlack thead {
    background: #eeeeee;
    }
    table.minimalistBlack thead.thead-title {
    background: #b0cbff;
    }
    table.minimalistBlack thead th {
    font-size: 12px;
    font-weight: bold;
    color: #000000;
    text-align: center;
    }
    table.minimalistBlack tfoot {
    font-size: 12px;
    font-weight: bold;
    color: #000000;
    }
    table.minimalistBlack tfoot td {
    font-size: 12px;
    }
    .text-center{
        text-align: center;
    }
    .text-left{
        text-align: left !important;
    }
</style>
<body>
    <table class="minimalistBlack">
        <thead>
            <tr>
                <th colspan="4">TRANSFER CALLUS</th>
            </tr>
            <tr>
                <th class="text-left"><strong>ID Pekerja:  </strong></th>
                <th class="text-left"><strong>Nama Pekerja:  </strong></th>
            </tr>
        </thead>
    </table>

    <table class="minimalistBlack" style="margin-top: 10px">
        <thead>
            <tr>
                <th rowspan="2">Tgl<br>Kerja</th>
                <th rowspan="2">Sample</th>
                <th rowspan="2">No <br> Block</th>
                <th rowspan="2">No <br> Botol</th>
                <th rowspan="2">Tgl <br> Obs</th>
                <th rowspan="2">Sub <br> Culture</th>
                <th rowspan="2">34 <br> Botol <br> Baru</th>
                <th rowspan="2">34 <br> Botol <br> X</th>
                <th rowspan="2">34 <br> Botol <br> 58</th>
                <th rowspan="2">Media</th>
                <th rowspan="2">Tgl <br> Media</th>
                <th rowspan="2">Jenis <br> Botol</th>
                <th rowspan="2">Laminar</th>
                <th rowspan="2">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @for ($x=1;$x<=$data['totalRow'];$x++)
                <tr>
                    <td class="text-center">{!! '&nbsp;' !!}</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                </tr>
            @endfor
        </tbody>
    </table>

</body>
</html>
