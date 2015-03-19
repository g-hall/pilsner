<?php
require_once('classes/Request.php');
$req = new Request();
$data = '';

// HTTP GET
if ($req->verb === 'GET') {

require_once('classes/Response.php');

    if ($req->url_elements[1] === 'about') {

        $about = new ObjectResponse('about', NULL);
        $data = $about->getResponse();

    } else if ($req->url_elements[1] === 'bios' && ((count($req->url_elements) == 2)
                || ((count($req->url_elements) == 3 && (!isset($req->url_elements[2]) || $req->url_elements[2] === ''))))) {

        $bios = new ArrayResponse('bios', NULL);
        $data = $bios->getResponse();

    } else if ($req->url_elements[1] === 'bios' && count($req->url_elements) == 3 && $req->url_elements[2] !== '') {

        $bio = new ObjectResponse('bios', $req->url_elements[2]);
        $data = $bio->getResponse();

    } else if ($req->url_elements[1] === 'repairs') {

        $repairs = new ObjectResponse('repairs', NULL);
        $data = $repairs->getResponse();

    } else if ($req->url_elements[1] === 'testimonials') {

        $testimonials = new ArrayResponse('testimonials', NULL);
        $data = $testimonials->getResponse();

    } else if ((empty($req->url_elements) || $req->url_elements[1] === 'index')) {

        $index = new IndexResponse('index', NULL);
        $data = $index->getResponse();

    } else if ($req->url_elements[1] === 'press' && ((count($req->url_elements) == 2)
                || ((count($req->url_elements) == 3 && (!isset($req->url_elements[2]) || $req->url_elements[2] === ''))))) {

        $press = new ArrayResponse('press', NULL);
        $data = $press->getResponse();

    } else if ($req->url_elements[1] === 'press' && count($req->url_elements) == 3 && $req->url_elements[2] !== '') {

        $pressItem = new ObjectResponse('press', $req->url_elements[2]);
        $data = $pressItem->getResponse();

    } else if ($req->url_elements[1] === 'contact') {

        $contact = new ObjectResponse('contact', NULL);
        $data = $contact->getResponse();

    }

        header("Access-Control-Allow-Origin: http://localhost:9000");
        header("Content-type: application/json");

        print $data;
        exit();
} // end GET

// HTTP POST
if ($req->verb === 'POST') {

    require_once('classes/DBadd.php');
    require_once('classes/Response.php');

    $data = json_decode(file_get_contents("php://input"), true);
    $db = '';

    if ($req->url_elements[1] === 'bios') {
        $data = $data['bio'];
        $db = 'bios';
    } else if ($req->url_elements[1] === 'testimonials') {
        $data = $data['testimonial'];
        $db = 'testimonials';
    }

    $add = new DBadd($db, $data);

    if ($add->wasDataSet()) {
        header("Access-Control-Allow-Origin: http://localhost:9000");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type");
        $resp = new ObjectResponse($db, $add->getLastInsertId());
        print $resp->getResponse();
        exit();
    } else {
        header("Access-Control-Allow-Origin: http://localhost:9000");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type");
        http_response_code(500);
        print $add->getErrorMessage();
        exit();
    }

} // end POST

// HTTP PUT
if ($req->verb === 'PUT') {

    require_once('classes/DBupdate.php');
    require_once('classes/Response.php');

    $data = json_decode(file_get_contents("php://input"), true);
    $db = '';
    $id = NULL;

    if ($req->url_elements[1] === 'about') {
        $data = $data['about']['body'];
        $db = 'about';
    } else if ($req->url_elements[1] === 'bios') {
        $data = $data['bio'];
        $db = 'bios';
    } else if ($req->url_elements[1] === 'contact') {
        $data = $data['contact'];
        $db = 'contact';
    } else if ($req->url_elements[1] === 'repairs') {
        $data = $data['repairs']['body'];
        $db = 'repairs';
    } else if ($req->url_elements[1] === 'testimonials') {
        $data = $data['testimonials'];
        $db = 'testimonials';
    }

    if ($req->url_elements[2] !== '' ) {
        $id = $req->url_elements[2];
    }

    $insert = new DBupdate($db, $data, $id);

    if ($insert->wasDataSet()) {
        header("Access-Control-Allow-Origin: http://localhost:9000");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type");
        $resp = new ObjectResponse($db, $id);
        print $resp->getResponse();
        exit();
    } else {
        header("Access-Control-Allow-Origin: http://localhost:9000");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type");
        http_response_code(500);
        print $insert->getErrorMessage();
        exit();
    }

} // end PUT

// HTTP DELETE
if ($req->verb === 'DELETE') {

    require_once('classes/DBdelete.php');
    require_once('classes/Response.php');

    $data = json_decode(file_get_contents("php://input"), true);
    $db = '';
    $id = NULL;

    if ($req->url_elements[1] === 'bios') {
        $data = $data['bio'];
        $db = 'bios';
    } else if ($req->url_elements[1] === 'testimonials') {
        $data = $data['testimonials'];
        $db = 'testimonials';
    }

    if ($req->url_elements[2] !== '' ) {
        $id = $req->url_elements[2];
    }

    $delete = new DBdelete($db, $id);

    if ($delete->wasDeleted()) {
        header("Access-Control-Allow-Origin: http://localhost:9000");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type");
        //$resp = new ObjectResponse($db, $id);
        //print $resp->getResponse();
        exit();
    } else {
        header("Access-Control-Allow-Origin: http://localhost:9000");
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Headers: Content-Type");
        http_response_code(500);
        print $delete->getErrorMessage();
        exit();
    }

} // end DELETE

// HTTP OPTIONS
if ($req->verb === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:9000");
    header("Access-Control-Allow-Methods: PUT, OPTIONS, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");
    print '';
    //exit();
} // end OPTIONS
?>