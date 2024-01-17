<?php
session_start();

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>    
    
    <link rel="stylesheet" type="text/css" href="vendor/datatables/datatables/media/css/jquery.dataTables.css">
    <link rel="stylesheet" href="vendor/datatables/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">

    <style>
        #loading-overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
            font-size: 20px;
            padding-top: 20%;
            z-index: 9999;
        }
    </style>
</head>
<body>

<div id="loading-overlay">
    Please wait while loading data...
</div>
<div class="row justify-content-lg-end">
    <a href="index.php" class="btn btn-sm btn-primary col-sm-2">Home</a>
    <a href="load-local.php" class="btn btn-sm btn-info col-sm-2">Analysis</a>
     <!-- Logout button -->
     <form method="post" class="col-sm-2">
        <input type="submit" name="logout" value="Logout" class="btn btn-sm btn-danger">
    </form>
</div>
<hr>
<h1 style="text-align: center">SMS Reminder Module - Analysis Reports</h1>

<hr>
<table id="datatable" class="display" style="width: 100%">
    <thead>
        <tr>
            <th>Facility Name</th>
            <th>Batch ID</th>
        
            <th>Sender ID</th>
            <th>Message Text</th>
            <th>Mobile Number</th>
            <th>Submit Date</th>
            <th>Charged</th>
            <th>Status</th>
            
                     
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
            <tr>
                <th>Facility Name</th>
                <th>Batch ID</th>
            
                <th>Sender ID</th>
                <th>Message Text</th>
                <th>Mobile Number</th>
                <th>Submit Date</th>
                <th>Charged</th>
                <th>Status</th>
                
                        
            </tr>
    </tfoot>
</table>

<?php
    function loadFromJsonFile() {
        $file = 'data.json';
        if (file_exists($file)) {
            $jsonData = file_get_contents($file);
            return json_decode($jsonData, true);
        }
        return null;
    }

?>    
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script  type="text/javascript" src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
    <script type="text/javascript" src="vendor/datatables/datatables/media/js/jquery.js"></script>
    <script type="text/javascript" src="vendor/datatables/datatables/media/js/jquery.dataTables.js"></script>
    <script  type="text/javascript" src="vendor/datatables/datatables/dataTables.buttons.min.js"></script>
    <script  type="text/javascript" src="vendor/datatables/datatables/buttons.html5.min.js"></script>

    <script type="text/javascript" src="vendor/datatables/datatables/buttons.flash.min.js"></script>
    <script type="text/javascript" src="vendor/datatables/datatables/jszip.min.js"></script>
    <script type="text/javascript" src="vendor/datatables/datatables/pdfmake.min.js"></script>
    <script type="text/javascript" src="vendor/datatables/datatables/vfs_fonts.js"></script>
    <script type="text/javascript" src="vendor/datatables/datatables/buttons.print.min.js"></script>

    <script>
    $(document).ready( function () {

        
        var data =<?php echo json_encode(loadFromJsonFile()); ?>;
        
        var table;

        // Mapping Facilities to DatimCodes
        facilityCodeMap = 
        {   "Asokoro District Hospital": "wp753KYAdno",
            "Azriel Hosptial Lugbe": "ZDD57v5qRpA",
            "Bethel Clinic and Maternity": "RYVTvf4hkWx",
            "Cornelian Maternity and Rural Health - Gidan Mangoro": "pp2AS0FyLHp",
            "Custom Staff Clinic": "e3W0JOHKtvn",
            "Diamond Medical Center": "sUryQ7n0dAg",
            "Evangelical Church of West Africa (ECWA) Medical Center": "V9AWhIYQ8Uv",
            "Evangelical Church of West Africa (ECWA) Health Clinic - Kabusa": "GQIkBebg3SA",
            "Faith Medical Center - Karimo": "YUsdxekuYrY",
            "Federal Staff Hospital - Jabi": "LmLBtmd8U43",
            "Federal Staff Hospital - Gwarimpa": "dtlt61TM6ac",
            "Freedomscan Medical Center": "gNhTYE0YB0P",
            "Garki Hospital Abuja": "SLDfqhuQVZW",
            "Nyanya General Hospital": "vdQKOn1w8Ql",
            "Karshi General Hospital": "r2S8m4Z1g4A",
            "Kubwa General Hospital": "x9IejBPCQVb",
            "Getwell Hospital": "eVRpUIGh9Lh",
            "Gidan Mangoro Primary Health Center": "ae7yf6GzRsl",
            "Gwagwa Primary Health Center": "ZVyH4YGTvGM",
            "Gwarimpa General Hospital": "xiXIP4UV1BR",
            "Idu Karimo Primary Health Center": "rdDoegrEtum",
            "Jahi Primary Health Center": "k1b8RGXowza",
            "Jikwoyi Medical Center": "w5ZwJg0X5D4",
            "Kagini Primary Health Center": "wF0endAxIJX",
            "King's Care Hospital": "wTmsnc4kenf",
            "Lugbe Primary Health Center": "E82uS1Gnznn",
            "Maitama General Hospital": "TJ3gInbwDXs",
            "Massan Clinic Limited": "gLfqoHRtXMC",
            "Medical Missions of Mary - Lugbe": "G5maKPztL5K",
            "National Hospital - Abuja": "meYf9FxUI4c",
            "National Institute For Pharmaceutical Research - Idu": "IGd2xA1uWF9",
            "Pan-Raf Hospital": "RJQw1UeNELQ",
            "Pigba Medical Center": "lTyNAjkImn8",
            "Police Clinic - Abuja": "MYIbzpMWd8N",
            "Karu Primary Health Center": "PbRh5MSdqkf",
            "Dutsen Garki Primary Health Center": "h5StehEhtb8",
            "Iddo Pada Primary Health Center": "WYI87feZ4qB",
            "Kpaduma Primary Health Center": "qXmgK5m4VaX",
            "Kabusa Primary Health Center": "GUXd34TURNZ",
            "Rapha Hospital": "K4SJ3bYXMq8",
            "Ruz Medical and Diagnostic Centre": "kRQE3PKNMcY",
            "Sisters of Nativity Hospital (SON) - Jikwoyi": "Nq0hQeEYCAk",
            "The Crown Hospital": "p7SR54qwbtD",
            "Yabisam Hospital": "rIR1wprAE7M",
            "Mpape Primary Health Center": "Gq6VRX4SDTV",
            "Cream Medics": "APriVNdVSRc",
            "360 Care Clinic & Maternity- Mpape": "b8wSBkFN6qj",
            "Jikwoyi Primary Health Center": "Jvd5y9U8yMI",
            "Standard Care Hospital": "zTOPNsW4lUA",
            "Nyanya One Stop Shop": "SHF865XzjPJ",
            "Kuchingoro Primary Health Center": "JI8s1z5gpoc",
            "Gwarinpa One Stop Shop": "FjX5IC6wJ1m",
            "International Center for Advocacy on Rights to Health": "GsRLJMhmuUz",
            "YOUTHRISE One Stop Shop": "qhdQUxH2m2P",
            "MABUSHI One Stop Shop": "dXFoflilImk",
            "Rainbow Hospital and Maternity": "zanCGyPSvTk",
            "State House Clinic": "HAxZv20iDuh",
            "fc Wuse District Hospital": "Vbs05uX6Y1i",
            "KPIF_Gwarinpa OSS": "GGVeC4Mwjk5",
            "fc AMAC": "uosU9sINAPe",
            "Apo Primary Health Centre": "Rx0f9PLrpxt",
            "Ayaura Comprehensive Health Centre": "pJLOwOE8q9K",
            "fc Abaji": "nX918whVxYP",
            "Abaji General Hospital": "Z226xvNYD7J",
            "Omega Hospital": "Rg6WyccbM1f",
            "Our Lady of Fatima Hospital - Bwari": "aQlACyagbgy",
            "Kogo Primary Health Center": "cKDxPChHFCF",
            "Sabon Gari Primary Health Center": "IJ5PG1Bw2VW",
            "St. Andrews Anglican Hospital - Kubwa": "v3TkEm3tIOC",
            "Sumit Hospital": "Z8B1VX79MbC",
            "Unity Clinic and Maternity": "z5gQ18jlgj5",
            "Bwari General Hospital": "KA9udHas5Nt",
            "Bwari Medical Center": "THd4hJ2BfQy",
            "Daughters of Charity (DOC) Hospital - Kubwa": "iJ6bV49PCve",
            "Dei Dei Comprehensive Health Center": "gCq0WqCmVqi",
            "Dominion Hospital": "OJgWGZkJlTc",
            "Gabic Divine Clinic and Maternity": "z3lEh9ayyzV",
            "Goodness Land Clinic and Maternity": "UkHphkpqSKb",
            "Laura Hospital and Maternity": "JORcUr1vjZ2",
            "M-Dali Hospital": "bnUu3uUtKQ2",
            "Excellence and Friends Management Consult (EFMC) Care Center (Modern Health Hospital)": "xKvwaYWM2BS",
            "fc Bwari": "JkmyrqDGshk",
            "Dutse Alhaji Primary Health Centre": "Ahcb9XhcWsi",
            "Byazhin Primary Health Centre": "LYlPd52expn",
            "fc St Mary Catholic Hospital - Gwagwalada": "iOoN7y6F2jt",
            "fc Gwagwalada": "V91ICEXLeks",
            "Alheri Kuntuku Clinic": "aYxFOTxAeHr",
            "Diamond Crest Hospital and Maternity": "RzBQByP3Hd7",
            "Jerab Hospitals": "b1FhDo7xSWO",
            "Joefag Alheri Clinic and Maternity": "JjiCVLEjZ1p",
            "Living Rock Hospital and Maternity": "NUcA8XMsSMI",
            "Minat Clinic": "ZvwAJoKvg8Y",
            "Dagiri Primary Health Center": "WRIX0OX3q6e",
            "University Of Abuja Teaching Hospital - Gwagwalda": "GW1w1chZMPR",
            "Zuba Primary Health Center": "rgzdfVQ9AK2",
            "Gwagwalada Clinic and Maternity": "xBjuRJxxetO",
            "Gwagwalada Township Clinic": "ZkdXdTHpcXN",
            "Anawim OSS": "NF1AxpCGCKk",
            "Gwagwalada KP One Stop Shop": "IEE3UZcwPu3",
            "Kuje Primary Health Center": "ICdkOHvCUgW",
            "Gaube Comprehensive Health Centre": "PLT2H2gBSYT",
            "fc Kuje": "qK6jo6jLIav",
            "fc Kwali": "sQbPVIKsxwT",
            "Rhema Hospital Kwali": "Q2Jqt7sTbPg",
            "Dabibako Primary Health Center": "WXTrdUZkyke",
            "Kwali General Hospital": "cp7lzrnlXEV",
            "Babbar Ruga Hospital": "Oj3ApqoMBRA",
            "General Amadi Rimi Speciality Hospital": "NTeQgW93Shk",
            "kt Batagarawa": "dURSfL69IMw",
            "kt Bindawa": "dutkWvnYS9i",
            "Bindawa Comprehensive Health Center": "ikwzTS1qZPO",
            "kt Bakori": "E7bvNe8kEF0",
            "Bakori Comprehensive Health Center": "sQIWOYH7aGF",
            "kt Baure": "eDBqlezxH0a",
            "Baure General Hospital": "sOl0lBuMNZQ",
            "Batsari General Hospital": "OcvqkWgsTnG",
            "kt Batsari": "duzPZ6Bukjf",
            "kt Charanchi": "aoi69JaAW3P",
            "Charanchi Comprehensive Health Center": "gHIPOF30a9F",
            "kt Dandume": "O3zSmYC7dRp",
            "Dandume Comprehensive Health Center": "cvb2P7zVKyD",
            "Danja Comprehensive Health Center": "aGsUsTkttSl",
            "kt Danja": "GbrZHS1OjjD",
            "kt Dan Musa": "H3TXFP8cHtr",
            "Danmusa General Hospital": "aZr5I6ph33j",
            "kt Daura": "QGI0cnzjZIP",
            "kt Daura General Hospital": "RdUzGWWWRlm",
            "kt Dutsinma General Hospital": "rN9LJD14rFQ",
            "kt Dutsin Ma": "PwTVkZPuTDA",
            "kt Dutsi": "oZK7wQ8AS05",
            "Kayawa Primary Health Center": "wKH0DQEUFpI",
            "Dutsi Comprehensive Health Center": "LnfdY78F2zL",
            "Faskari Comprehensive Health Center": "QdywMJPubBx",
            "kt Faskari": "AUZAz1oqQ7F",
            "kt Funtua": "uYJi1Sc1OlZ",
            "Funtua Safe Heaven OSS": "Xva29rVyQjS",
            "Funtua General Hospital": "yutnuUG0Y6L",
            "Jibia General Hospital": "Gbqyxn5pAkd",
            "kt Jibia": "tiBbmPwovlH",
            "kt Kaita": "Mq9Y1QEWTTf",
            "Kaita Comprehensive Health Center": "UeR5QcsCmvb",
            "Kafur Comprehensive Health Center": "LGQjlfgEZeY",
            "kt Kafur": "UbrsWhXj70k",
            "kt Kankara": "Oc78WrIzv4O",
            "Kankara General Hospital": "ddKNHScUykq",
            "Kankia General Hospital": "kW2bdDgnnuB",
            "kt Kankia": "WeHFx8CmMqV",
            "Kusada Comprehensive Health Center": "A6eUl9KA4XD",
            "kt Kusada": "ACvgKttmG58",
            "Turai Yar' Adua Maternal and Child Health": "ZMJqjpkNnR6",
            "Katsina Safe Heaven OSS": "KVdLBmuVcrZ",
            "Kofar Kaura Maternal and Child Health": "dExUAXKkMVe",
            "kt Katsina": "XDgdj9Pdsiq",
            "Abdull Jalil's (A.J.S.) Out Patient Clinic": "ODRBvOCbd8u",
            "Alheri Clinic": "Fz2R4yrPdwv",
            "Federal Medical Center - Katsina": "E91YP7z3knH",
            "Katsina General Hospital": "fuKySzRNkla",
            "kt Kurfi": "Lge2fmokEKv",
            "Kurfi General Hospital": "xExv2tFvkeV",
            "kt Mani": "AmRHP7t5r7s",
            "Mani General Hospital": "jKBD0VRXwFb",
            "kt Mai'Adua": "jHgdqXImWVH",
            "Comprehensive Health Center Maiadua": "kvCBgdfgm0B",
            "kt Malumfashi": "b3NvwiCilSH",
            "Malumfashi General Hospital": "beyor8cvEjY",
            "Malumfashi Maternal and Child Health Clinic": "lyOHQgkWmHC",
            "kt Mashi": "CxUtCqUjMHr",
            "Mashi Comprehensive Health Center": "piHoxArdkJx",
            "kt Musawa": "A8BaTswwST4",
            "Musawa General Hospital": "Y8ot1KUq8dt",
            "kt Matazu": "gp5qHsPu52e",
            "Matazu Comprehensive Health Center": "gBbkJ5wBQqq",
            "kt Ingawa": "clxrlIljUkQ",
            "Ingawa General Hospital": "zjI6BntkZWL",
            "kt Rimi": "KX6WxCBO8sd",
            "Rimi General Hospital": "Ynp7xCvewkd",
            "kt Sabuwa": "P5LcMSudkwP",
            "Sabuwa Comprehensive Health Center": "GvAcMgdITxC",
            "Sandamu Comprehensive Health Center": "teAYgWqhGit",
            "kt Sandamu": "F8RVAawTj3q",
            "kt Safana": "smhArjDWLuv",
            "Safana Comprehensive Health Center": "eyvLB7sGLCi",
            "Zango Comprehensive Health Center": "qib4fj60YVt",
            "kt Zango": "cSqqluzYmPt",
            "KPIF_Akwanga OSS": "PRLTfziBO1L",
            "na Akwanga": "tKAh5zuMYhT",
            "Our Lady of Apostles Hospital - Akwanga": "K57MkWyAj2Y",
            "Akwanga Primary Health Care Center": "Jj4IO088cM3",
            "Andaha Primary Health Care Center": "JqudjV9JqmP",
            "Gudi Primary Health Care Center": "CfgHRysxMXP",
            "Nunku Primary Health Care Center": "R48oGDJNgnv",
            "Royal Hospital": "DOWX4SxIJkP",
            "Mochu Clinic": "LGx7o765SJ4",
            "Rinze Primary Health Care Center": "qZmwoo3H62F",
            "Orient Hospital": "p4IDnp3bLHA",
            "Akwanga General Hospital": "JSAYmpfl5jW",
            "Awe General Hospital": "n689TO852Vd",
            "Jatau Clinic": "CObWhYb9CZS",
            "na Awe": "K17UuwVu7Jf",
            "na Doma": "tGdZvabgJw6",
            "Idadu Primary Health Center": "YVk6aoeOl9Z",
            "Rukubi Primary Health Center": "tA9VHG3QhOC",
            "Shalom Clinic": "GdAqcY5seM7",
            "Zumunta Yelwa Ediya Clinic": "bK8mKs9KWZ1",
            "Doma General Hospital": "deV3U03tS8F",
            "Doma Primary Health Center": "xEytSJqElVY",
            "Owoche Clinic and Maternity": "U5N1HnNpRM2",
            "New Era Clinic": "FzWsHN37gF0",
            "Arumangye Bosco Primary Health Center": "A0ta1i6orqf",
            "Sabo Clinic": "PRRdNaG3twk",
            "Burum-Burum Primary Health Center": "f9EYpOJXnW2",
            "Okpatte Primary Health Center": "tIs747BlDIu",
            "Garaku General Hospital": "AARV8rqn3By",
            "Mak-lin Clinic": "wjalahQZz3U",
            "Minlap Clinic": "NvELLs4QbaL",
            "Tamaiko Clinic": "F069O0zAN9F",
            "na Kokona": "JBqBHvcmICE",
            "na Keffi": "gZ8SPQ2CKQG",
            "Innovative Biotech": "Gweif86aJmA",
            "Amosun Maternity Hospital": "RoHlm7dugPM",
            "Anguwan Waje Primary Health Care": "StdZqGX4NXD",
            "Federal Medical Center - Keffi": "Ro8QYYh2EVH",
            "Keffi General Hospital": "P57IvbVY9gk",
            "Shukura Specialist Hospital": "BDW4hbozh4N",
            "Evangelical Reformed Church of Christ (ERCC) Clinic - Keffi": "lQuZB3TSI1r",
            "Imani Clinic": "DM5ZL50xECQ",
            "Kadarko National Primary Health Care - Giza Development Area": "JWxRzMCgQo6",
            "Keana General Hospital": "swGd0hz84FV",
            "na Keana": "IbcfQbdZNdC",
            "KPIF_Karu OSS": "vzy7MBuPWQ0",
            "na Karu": "EusV10zkN3t",
            "Ojone Favour": "Bm1SmqJLceB",
            "Karu KP one stop shop": "KLx83uYKVcC",
            "Nyanya Gbagi Primary Health Care Center": "J5bNl9I7Zao",
            "Adonai Hospital": "IMGvQmgPyOM",
            "Alheri Clinic and Maternity_U Turn (Karu)": "p1jsPOYBrXI",
            "Alheri Clinic and Maternity": "HTjveh0VnKk",
            "Anointed Clinic": "bdhQrIQTyXm",
            "Aso Panda Primary Health Care Center": "FY9Jon1iRLU",
            "Auta Primary Health Center": "IAIrKgIdFUg",
            "Best Clinic": "EbXYU9tNm8T",
            "Gidan Zakara Primary Health Center": "fpOBJBEXXGy",
            "Gora Primary Health Center": "MzCytx8YIJR",
            "Gunduma Primary Health Center": "kef3pIqRSsN",
            "Jankanwa Primary Health Center": "HaeGk76xBrx",
            "K-Health (Adult Adolescent Program)": "fg7rWUILcLg",
            "Kingscare Hospital": "hDgdb8vCEOa",
            "Kpamu Shammah Hospital": "fqtnTvp5Lds",
            "Maraba Clinic and Maternity": "Hrnl2dZ0ymS",
            "Maraba Gurku Primary Health Center": "ijx91LFbWMZ",
            "Mararaba Guruku Medical Center": "tyyZQSN5D4p",
            "Masaka Primary Health Care": "zEA6UJ3Z2Dg",
            "Mayday Specialist Hospital And Maternity": "cHhkQZN15Cx",
            "Mission Hopsital": "ZmxdYXeH5Cd",
            "Nakowa Clinic": "D1T4Yg07e0C",
            "Karu Primary Health Center": "sB8cWMd8QyO",
            "Nisi Hospital": "MYgtY8vQtdn",
            "Pijag Maternity Home": "ql5itoWziK0",
            "Uke General Hospital": "DwZJjC3rfCJ",
            "Aboki Clinic": "qkyUy0iQUIT",
            "Adogi Primary Health Care": "yC86zV8BNHN",
            "Agu Hospital": "hFhGouLz8Ub",
            "Barkin Abdullahi Primary Health Center": "LpwpgWBHZR0",
            "Diamond Clinic - Lafiya": "e5ZNLK1GbTi",
            "Gosha Clinic And Maternity": "WSMZ2SYgAFZ",
            "Graceland Clinic - Lafiya": "iJszAqdhmdK",
            "Lafia Clinic": "MXAJ9B6wUxm",
            "M&D Hospital": "RK50nYp7Rum",
            "Namu Clinic": "MqsGY8t2wzm",
            "Olivet Medical Center": "eiqXUByOSID",
            "Oshyegba Medical Center": "faDHc74njXW",
            "Sadanji Medical Center": "KBrRnwCYJdj",
            "Sauki Hospital": "klhrcE8J7I9",
            "The Chrane Hospital": "Q91KTmTDhIb",
            "Tudun Gwandara Primary Health Center": "I5uopLtCGdv",
            "Voice of Islam Hospital": "Izlht3DoxfC",
            "Wadata Primary Health center": "VQNCQLUsMpe",
            "Assakio Primary Health Center": "BjlE93CNlhG",
            "Azuba Bashayi Primary Health Center": "CW3pQyyW4mc",
            "Dalhatu Araf Specialist Hospital": "AlHWYsy5u3m",
            "Doma Road Primary Health Care Center": "qsS9YwrZSND",
            "Gidan Maiakuya Primary Health Center": "sPTIU3vUEd6",
            "New Market Road Primary Health Center": "P9buaLoYfV6",
            "Shabu Model Comprehensive Center": "ZBFgkyECWkA",
            "Lafia KP One Stop Shop": "OsIeuStyqTh",
            "Kowa Hospital": "DEdtkG9rFC9",
            "na Lafia": "TMbbzVpDDad",
            "PHC Tudun Kauri": "k7O9aErToFP",
            "na Obi": "ljMQwl1DOCb",
            "Catholic Maternity and Rural Health Center -Agwatashi": "PKIGGA1CoX9",
            "na Obi General Hospital": "uIi4K8uWO9w",
            "Azomeh Clinic": "R43psgnri43",
            "Evangelical Reformed Church of Christ (ERCC) Clinic - Murya": "UAiWkGylFj4",
            "Ikon Allah Iroh Hospital": "Ze1KoeUk22P",
            "Imani Clinic": "CpAIWDZlics",
            "Imon Primary Health Center": "djfbPI3n1OQ",
            "Mother and Child Welfare Clinic 1": "KrNjWYgoaIe",
            "Obi Primary Health Care Center - Agyragu": "kNVBrpSSarY",
            "St. Bernards Clinic - Akanga": "nHzMmotg0fA",
            "Tudun Adabu Primary Health Center": "yBFTsDiVfQ4",
            "Omatdefu Clinic and Maternity": "tfUTOpsD1OR",
            "Nasarawa Eggon General Hospital": "KMLMCZ5fC94",
            "na Nasarawa Eggon": "MZ2jaWNX1Oq",
            "Alushi ERCC": "lJ0oL2bbgaN",
            "General Hospital Mararaba Odege": "bh1R2uI3F5y",
            "na Nasarawa": "PM4sFTtfJQa",
            "na Nasarawa General Hospital": "PIM6KHQXxrB",
            "Alpha Medical Center": "A80lDEx4cG1",
            "Ara 2 Primary Health Center": "SXB1EwVEKff",
            "Henad Medical Center": "phpPcHXgNFs",
            "Laminga Primary Health Center": "nBkgFP6ol6e",
            "Loko 2 Kekura Road Primary Health Care Center": "RbOZGOuyDlz",
            "Loko Primary Health Center": "D62rPjDOlCP",
            "Main Town Nasarawa Primary Health Care": "uIiPHxfusOL",
            "Mararaba Odege Primary Health Care Center": "EP1dUbwLtP4",
            "Marmara Primary Health Center": "a1NJaUQ6ydV",
            "Nasara Clinic - Ara": "x0fdu53uYLh",
            "Nasarawa Medical Center": "RTcFVqHfMZH",
            "na Toto": "rQBtRTixEgZ",
            "Toto General Hospital": "c4hHnOsaKjV",
            "Umaisha General Hospital": "cxlo3HBSPIe",
            "na Wamba": "ldTia6dCApK",
            "Wamba General Hospital": "mfodk1V16v0",
            "ri Akuku Toru": "gdQCMg31viH",
            "Abonnema Comprehensive Health Centre": "dpkdTE4L9l1",
            "ri Abua-Odual": "ObllIYWxwRA",
            "Abua General Hospital": "s7iNeUuoOz1",
            "Oyigbo Comprehensive Health Centre": "UlX4JORbkIa",
            "Divine Wisdom Hospital": "ZrOJJd1WFi2",
            "Heritage Medicare Hospital": "JUwsTI1Eo4h",
            "Living Water Hospital": "x7jYYtMtfdY",
            "ri Oyigbo": "I0tQo5iW5NZ",
            "ri Ahoada East": "GBwKRrWGuVY",
            "Ahoada Gbeye Clinic Annex": "GpVQHGY264w",
            "Ahoada Model Primary Health Centre": "FuflWBrELyY",
            "Ula Upata Beulah Clinic": "ovKn2kFyVu1",
            "Ahoada General Hospital": "a5TU20BYdFY",
            "ri Omumma": "xdzbsxe1MQa",
            "Obioha Model Primary Health Centre": "aNkNOQjVs3o",
            "ri Asari-Toru": "SExBRzuDiYb",
            "Buguma Model Primary Health Centre": "gvUEfvgtJ95",
            "Bonny Health Centre": "tCN3TSuXyOc",
            "St. Charles Surgery": "Hfn0KaTBQmf",
            "St. Peter's Clinic": "lw44OGucUJT",
            "Bonny General Hospital": "FZFVPhZzikF",
            "Bonny KP One Stop Shop": "pNzfxjpSiDy",
            "ri Bonny": "kyuGgrpjwZF",
            "ri Khana": "sZumF7t54ek",
            "Bori General Hospital": "QsChxCxPcqL",
            "Pope John Paul Clinic": "cZejFgfeC75",
            "Beeri Model Primary Health Centre": "m5JBYmg2y9O",
            "Bori Inadum Medical Centre": "jbjCB91Bild",
            "Opuoko Model Primary Health Centre": "hgyAkSpLcLo",
            "Degema General Hospital": "uTSKSwmTe3i",
            "ri Degema": "O9l6BemolrN",
            "ri Ogu-Bolo": "wXnjvr9p2zU",
            "Ogu Primary Health Centre": "bHgroUhbsqe",
            "Okomoko General Hospital": "XwODacI3oni",
            "Umuebule Cottage Hospital": "rSvimK96wuu",
            "ri Etche": "y5pPli7WP1V",
            "ri Ahoada West": "Nd9sbZcqorj",
            "Lengro Medical Centre": "aM2ElZuLpx2",
            "Joinkrama General Hospital": "fCnLZTsv0O9",
            "Bodo General Hospital": "l03CW5zFRnV",
            "Terrebor General Hospital": "nTOggT8y4RW",
            "Dumkil Medical Centre": "yqVkTJjtwUh",
            "ri Gokana": "N0dXOBSVO7k",
            "Kpor Model Primary Health Centre": "MA9oxYM6KtP",
            "Bomu Model Primary Health Centre": "Yq0F3u7p7fX",
            "ri Okrika": "nNo5gV3to2B",
            "Okrika General Hospital": "hlKqAEwWVbh",
            "Ayungu Biri Model Primary Health Centre": "FTowClyX4hi",
            "Ibaka Model Primary Health Centre": "UlDC8p3klui",
            "Ogoloma Model Primary Health Centre": "WKfFn4czH1v",
            "Okrika Mainland Clinic": "Oog0FhA3YKy",
            "Ndele Model Primary Health Centre": "O3aP7XpQF7p",
            "Rumuji Model Primary Health Centre": "tuynia9m3nq",
            "ri Emohua": "UGwCX5IBQQj",
            "ri Eleme": "UdnUbXV7O9o",
            "Agbonchia Model Primary Health Centre": "u4nsK8x6P0T",
            "Dorkson Medical Clinic": "fIwVIJYge6w",
            "Ebubu Model Primary Health Centre": "ELZiKtYXYYY",
            "Morning Star Hospital": "C07yjqz8v4F",
            "Omas Medical Centre & Maternity": "xAr8D5EJkhT",
            "Onne Medical Centre": "nQ2toqXHBiG",
            "Onne Model Primary Health Centre": "XmHZEGimDTM",
            "Sonabel Hospital (HQ)": "TZ0Zsf2KLYV",
            "St Matthews Clinic": "yqcmwat3bJs",
            "Vinkas Clinic": "ZNqpzwElUB7",
            "Nchia Health Centre": "OxwWC4ptpOq",
            "Akpajo Model Primary Health Centre": "Zn2wY00xAAe",
            "Eleme General Hospital": "VI7swW6DIGB",
            "Ngo General Hospital": "GzexcKgtftB",
            "ri Andoni": "pp8zV2OXmvU",
            "ri Opobo-Nkoro": "RgaAXlRX33Y",
            "ri Port-Harcourt": "y3iWYd0poWG",
            "Resource Centre": "mml1V9rYVbf",
            "Health of the Sick": "s0SI9pIe68w",
            "Bunduama Model Primary Health Centre": "KaCdpgU73iu",
            "Churchill Model Primary Health Centre": "KqlsnaLhICm",
            "Elekahia Model Primary Health Centre": "SVYfCi7sPwh",
            "Hearth Health Hospital": "Hwv1DzJ2pKj",
            "Megacare Hospital": "p0oA1VEoN12",
            "Mgbundukwu (Okija) Model Primary Health Centre": "Vp53teY94db",
            "New Mile one Hospital": "iBDi4PBN0wj",
            "Okuru-ama Model Primary Health Centre": "KZuTHOKjWwG",
            "Orogbum Primary Health Centre": "jEi2QgZlEfC",
            "Ozuboko Model Primary Health Centre": "d8p2Bn2zyNC",
            "Police Medical Clinic": "PROZ69JNyEL",
            "Potts Johnson Model Primary Health Centre": "n5I9w5PDkOc",
            "St Patricks Hospital": "rlZ43pTGL8E",
            "Sterling Specialist Hospital": "Inu1ikx6d0b",
            "Meridian Hospital": "RmDJfQHGtay",
            "Rivers State University Teaching Hospital": "FH7LMnbnVlT",
            "Erema General Hospital": "F5MHLc59Vjo",
            "Gbeye Clinic": "wvYBFvU7NrE",
            "Okwuzi Model Primary Health Centre": "cEq4J2KblZf",
            "Omoku General Hospital": "c8mPyHVf7sh",
            "Omoku Model Primary Health Centre": "qoM5d6LxtRm",
            "Omoku Prize Medicals Limited": "SkRUKUsF4QS",
            "ri Ogba-Egbema-Ndoni": "xZFseUogJWo",
            "ri Obio-Akpor": "QUXP7f16K3Z",
            "KPIF Obio-Akpor KP OSS": "KFbRZKvXpb3",
            "Initiative for Advancement of Humanity (IAH)": "Y3VlUBi4kD9",
            "Eliozu Model Primary Health Centre": "fUNUMu5BYHG",
            "College of Health Technology Clinic": "hfyt7OwScuM",
            "University of Portharcourt Teaching Hospital": "Ke1y8vsmeC4",
            "Palmers Hospital Ltd": "n7nMw2mokBJ",
            "Obio Cottage Hospital": "ahGtSIJrPnm",
            "Alphonso Hospital": "qII33eHwDcJ",
            "April Clinic": "wxmE6Yka5fx",
            "Atinu Critical Care Hospital": "wbtGFDS6upN",
            "Elelenwo FSP Health Centre": "Pcf3kHVaQuk",
            "Eneka Model Primary Health Centre": "BpdDwXvjnv2",
            "Iriebe Primary Health Centre": "qIjWndeKXZq",
            "Karpearl Hospital": "eGAsiJ1wRoQ",
            "Kendox Medical Services": "tU6NlKR1Brm",
            "Ozuoba Model Primary Health Centre": "JiHYrcVKLu1",
            "Pathfare Clinic": "LRG1636p7PZ",
            "Rivon Clnic": "w1hWQ4Jqaoi",
            "Rumueme Model Primary Health Centre": "sc1imOzTy4t",
            "Rumuepirikom Model Primary Health Centre": "dIgL5MRZW07",
            "Rumuokrushi Model Primary Health Centre": "BQXuCQIrKkZ",
            "Rumuolumeni Model Primary Health Centre": "JAwgztMuSEX",
            "Sonabel Medical Centre & Hospital": "RldFanY7hXq",
            "Splendour Hospital Ltd": "v9WGtsQxzhS",
            "Spring Hospital": "eFnf5Qs8ffZ",
            "St. Martin's Hospital": "OTYun1wBcn7",
            "Woji Cottage Hospital": "bcGVjGrCwyD",
            "Rumuodomaya Model Primary Health Centre": "bn8DOikbEEI",
            "Rumuigbo Model Primary Health Centre": "iIXcY9hO9pV",
            "Nonwa Model Primary Health Centre": "BdGiK6cRTho",
            "Bangoi Primary Health Centre": "AC85PHrGLPq",
            "Bunu Model Primary Health Centre": "orysJSDUuqU",
            "Kpite Model Primary Health Centre": "cdGf7E0iVWK",
            "ri TaiÂ": "tFy20OB0Wyw",
            "ri Ikwerre": "SL33bchlaKT",
            "Igwuruta Model Health Center": "pDLerWiHQ0t",
            "Aluu Model Primary Health Centre": "Bp386GWe6cG",
            "Mbodo Aluu Primary Health Centre": "l79OCqShrlD",
            "Isiokpo General Hospital": "XFDJkiGqk2X"
        }

        function loadData(data) {
            $('#loading-overlay').show();
            
            if (data && data.length > 0) {

                if (table) {
                    table.clear().rows.add(data).draw();
                } else {
                    // ... (existing DataTable initialization code)
                    $('#datatable thead tr').clone(true).appendTo('#datatable thead');

                    $('#datatable thead tr:eq(1) th:not(:last)').each(function(i) {
                        var title = $(this).text();
                        $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" value="" />');

                        $('input', this).on('keyup change', function() {
                            if (table.column(i).search() !== this.value) {
                                table
                                    .column(i)
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
                    
                    table = $('#datatable').DataTable({
                        data: data,
                        "columns": [
                                    { 
                                        data: function (row) {
                                            // Get the last eleven characters of messageText and map to facility code
                                            var lastElevenChars = row.messageText.slice(-11);
                                            var facilityName = Object.keys(facilityCodeMap).find(key => facilityCodeMap[key] === lastElevenChars);
                                            return facilityName || 'Unknown'; // Default value if no mapping found
                                        }
                                    },
                                    { data: 'batchID' },
                                    // { data: 'messageID' },
                                    { data: 'senderID' },
                                    {
                                        data: function (row) {
                                            // Trim off the last thirteen characters from messageText
                                            return row.messageText.slice(0, -13);
                                        }
                                    },
                                    { data: 'mobileNumber' },
                                    { data: 'submitDate' },
                                    { data: 'charged' },
                                    { data: 'reports[0].status' },
                                    // { data: 'reports[0].smscID' },
                                    // { data: 'reports[0].reportDate' }                
                                ],
                                
                        pageLength: 100,
                        searching: true,
                        dom: 'Bfrtip', 
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
                        initComplete: function () {
                            $('#loading-overlay').hide();
                        }
                    });
                    
                }
                $('#loading-overlay').hide();
            } else {
                $('#loading-overlay').hide();
                console.error('No data available.');
            }
        }

        // Load initial data
        loadData(data);
        
    });
</script>

</body>
</html>
