<!doctype html>
<html lang="en">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta charset="UTF-8">
<title>@yield('title','0000')</title>

<style>
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    
    .text-center{
        align-items: center;
        justify-content: center;
        text-align: center;
    }
	.w3-border {
        border: 1px solid #ccc !important;
    }
    .ml {
        margin-top: 10px !important;
        margin-bottom : 10px !important;
    }
    .tblMarginTop {
        line-height:25px;
        border: 1px solid #000 !important;
        border-radius: 16px;    
        
        background: #f2f2f2;
    }
    

</style>
</head>
<body>
    @yield('content')
</body>
</html>