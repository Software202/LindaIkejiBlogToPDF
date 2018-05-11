<?php


// $sample_time = preg_match("/\d+\:\d+\s*(AM|PM)/", " by Linda Ikeji at 11/05/2018 1:42 PM", $match);

// print_r($match);

// die( (String) strtotime($match[0]));
//
ini_set("maximum_execution_time", 3000);
ini_set("memory_limit", '1000M');

//

require "simple_html_dom.php";
require "vendor/autoload.php";

use Dompdf\Dompdf;


// $dompdf->setPaper('A4', 'landscape');

$url = "https://www.lindaikejisblog.com/";
$html = file_get_contents($url);

$html = str_get_html($html);

// $c = $html;



// var_dump($html);

// die;

// echo file_get_contents($url);
// die;

$number = 1;

// echo $html->find(".story_title a", 0)->href;

// die;


// print_r($html->find(".story_title a")->href);

// die;
foreach ($html->find(".story_title a") as $post_links) {
	# code...

	$post_links = $post_links->href;

	$link_content = file_get_contents($post_links);

	$link_content = str_get_html($post_links);

	// echo $link_content;

	$title = trim($link_content->find("article h1.title",0)->plaintext);
	// $date = 
	// $number = ;
	$body_cont = trim($link_content->find("summary.description",0)->plaintext);

	$body = $body_cont;

	$post_age = $link_content->find("article .post_age",0)->plaintext;

	$post_date = preg_match("/\d+\/\d+\/\d+/", $post_age, $age_match);

	$post_date = $age_match[0];

	$post_time = preg_match("/\d+\:\d+\s*(AM|PM)/", $post_age, $time_match);

	$post_time = $time_match[0];


	$weeks = "Week ".floor((time() - strtotime($post_time))/7);

	//Create folder if not exists

	if(!is_dir($weeks))
	{
		mkdir($weeks);
		chmod($weeks, 777);
	}

	$file_name =  preg_replace("/\W+/is", '-',"{$weeks}/$post_date at {$post_time}.pdf");


	// $post_week = ;

	$feature_img = "<img src='".$link_content->find("img",0)->src."' />";

	$number_foot = '<div style="width: 100%; text-align: center; padding; 5px; color: #000;">$number.</div>';


	$post_content = "$feature_img $title $body <br /> Link to Post: $post_links <br /> $number_foot \n";


	// echo $post_content;

	$number++;

	$dompdf = new Dompdf();

	$dompdf->loadHtml($post_content);

	// Render the HTML as PDF
	$dompdf->render();

	$output = $dompdf->output();


    file_put_contents("./posts/$file_name", $output);
	// unset($link_content);

	die;
}


?>