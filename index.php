<?php


// die(__DIR__);


// $sample_time = preg_match("/\d+\:\d+\s*(AM|PM)/", " by Linda Ikeji at 11/05/2018 1:42 PM", $match);

// print_r($match);

// die( (String) strtotime($match[0]));
//
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
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
$imgi = 0;
foreach ($html->find(".story_title a") as $post_links) {

	$image_src = $html->find(".img_view img", $imgi)->src;

	// die($image_src);
	# code...

	$post_links = $post_links->href;

	$link_content = file_get_contents($post_links);

	$link_content = str_get_html($link_content);

	// echo $link_content;

	$title = trim($link_content->find("article h1.title",0)->plaintext);
	// $date = 
	// $number = ;
	$body_cont = trim($link_content->find("summary.description",0)->plaintext);

	$body = $body_cont;

	$post_age = $link_content->find("article .post_age",0)->plaintext;

	$post_date = preg_match("/\d+\/\d+\/\d+/", $post_age, $age_match);

	$post_date = $age_match[0];

	$post_time =  preg_match("/\d+\:\d+\s*(AM|PM)/", $post_age, $time_match);

	$post_time = $str_pst_time = $time_match[0];

	// echo  $post_date." ".$post_time;

	// die(date('d/m/Y h:i A')."<br /> $post_date $post_time");
	// die($post_date." ".$post_time);

	// print_r(new DateTime());

	// die;

	$new_time_seconds = DateTime::createFromFormat('d/m/Y h:i A', $post_date." ".$post_time);

	// print_r($new_time_seconds);


	$post_time = strtotime($new_time_seconds->format('Y-m-d H:i:s'));

	$post_time = time() - $post_time;

	// die;

	// die(time()." $post_time");

	// $subtr = time() - $post_time;

	// echo $subtr;

	// die;

	//to weeks

	// $togo = $_SERVER['DOCUMENT_ROOT'];
	$weeks =  preg_replace("/\W+/is", '-',"Week-".floor($post_time/604800));

	//Create folder if not exists

	if(!is_dir("./posts/$weeks"))
	{
		mkdir("./posts/$weeks");
		// die('yes');
		chmod("./posts/$weeks", 0777);
	}

	$fn =  preg_replace("/\W+/is", '-',"$post_date at $str_pst_time");


	$file_name = "$weeks/$fn.pdf";

	// $feature_img_src = $link_content->find(".img-responsive",0);

	// var_dump($feature_img_src);

	// echo $feature_img_src;

	// die;


	$file_name_ext = pathinfo($image_src)['extension'];

	$rand_filename = uniqid().".{$file_name_ext}";

	$image_bin = file_get_contents($image_src);

	file_put_contents("./images/$rand_filename", $image_bin);

	$new_image_src = $_SERVER["DOCUMENT_ROOT"]."/Hack-Friday/images/$rand_filename";

	// echo $_SERVER['DOCUMENT_ROOT'];

	// echo $new_image_src;




	// file_put_contents("$new_image_src", $image_bin);



	//


	// $post_week = ;

	// echo $link_content->find("img",0)->src;
	$feature_img = "<center><img src='".$new_image_src."' width='500px' /></center>";

	$number_foot = '<br /><div style="width: 100%; text-align: center; padding; 5px; color: #000;">'.$number.'.</div>';


	$post_content = "$feature_img <h1 style='text-align: center'>$title</h1> $body <br /> <br />Link to Post: <a href='$post_links'>$post_links</a> <br /> $number_foot \n";


	// echo $post_content;


	$dompdf = new Dompdf();

	$dompdf->loadHtml($post_content);

	// Render the HTML as PDF
	$dompdf->render();

	$output = $dompdf->output();

	 

	// die("posts/$file_name");
    if(file_put_contents("./posts/$file_name", $output));
    $links_page[]= "$number. <a target='_blank' href='http://".$_SERVER['HTTP_HOST']."/Hack-Friday/posts/$file_name'>".$_SERVER['HTTP_HOST']."/Hack-Friday/posts/$file_name</a>";
    /*)
    {
    	die('Yes!');
    }
    else
    {
    	die('No!');
    }*/
	// unset($link_content);
	$imgi++;
	$number++;
}
	echo "Links to the generated PDF Results: </br>";
    echo implode("<br />", $links_page);


?>