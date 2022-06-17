<?php
include_once __DIR__ . '/vendor/autoload.php';
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

if(isset($_GET['site'])){

    // import the google api key for website insights retirieved from 
    // https://developers.google.com/speed/docs/insights/v5/get-started
    $api =getenv("GOOGLE_API_KE");
    $site =$_GET['site'];
    
    $adress="https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$site&category=CATEGORY_UNSPECIFIED&strategy=DESKTOP&key=$api";

    $curl_init = curl_init($adress);

    curl_setopt($curl_init,CURLOPT_RETURNTRANSFER,true);
    

    $response = curl_exec($curl_init);

    curl_close($curl_init);
    
    $googledata = json_decode($response,true);


    $snapdata = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"];

    $snap =$snapdata["screenshot"];


	Configuration::instance([
		'cloud' => [
			'cloud_name' => getenv("CLOUDINARY_NAME"),
			'api_key' => getenv("CLOUDINARY_API_KEY"),
			'api_secret' => getenv("CLOUDINARY_API_SECRET")
		],
		'url' => [
			'secure' => true
		]
	]);

	$response2 = (new UploadApi())->upload($snap['data'], [
		'resource_type' => 'image',
		]
	);
}
 
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Website screenshot</title>
 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <meta name="theme-color" content="#7952b3">
 
  </head>
  <body>
    
 
<div class="container py-3">
  <header>
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <h3 class="display-4 fw-normal">Website screenshot and save to Cloudinary</h3>
    </div>
  </header>
 
  <main>
    <form >
        <div class="form-row">
            <div class="form-group col-md-4 offset-4">
                <label  class="sr-only">Site</label>
                <input type="text" class="form-control" name="site" placeholder="https://site.com">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4 offset-4">
                <button type="submit" class="btn btn btn-dark">Capture shot</button>
            </div>
        </div>
    </form>
 
 
    <h2 class="display-6 text-center mb-4">Capture shot</h2>
 
    <div class="col">
        <?php if(isset($_GET['site'])){ ?>
            <!-- Display the ima using the screenshot url capture from response -->
            <img class="img-fluid" src="<?=$snap['data']?>" alt="snap">
        <?php }else { ?>
            <!-- If site is not yet set just provide message to enter site name -->
            <h2 class="text-muted text-center mb-4">Site name enter</h2>
        <?php  } ?>
    </div>
  </main>
 
</div>
  </body>
</html>