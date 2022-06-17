### Capture any website Screenshot using PHP

## Introduction
Screenshots let you capture exactly what you're seeing on your screen to share with others or reference later. This article will demonstrate how much can be achieved using PHP and also manipulate Cloudinary services with such a service

## Codesandbox

Check the sandbox demo on  [Codesandbox](/).

<CodeSandbox
title=""
id=" "
/>

You can also get the project Github repo using [Github](/).

## Prerequisites

Entry-level html and php knowledge.

## Setting Up the Sample Project
Create a new folder: `website2imgphp`
Inside the new folder's terminal use the command `componser init`. You need to have `php` and `composer` downloaded to your machine.

Follow the composer procedure which will help hand'e the necessary project dependancies. When asked to search for a package, serch for `clouidinary`


## Cloudinary
[Cloudinary](https://cloudinary.com/?ap=em) reffers an end-to-end image and video-management solution for websites and mobile apps, covering everything from image and video uploads, storage, manipulations, optimizations to delivery.Our app will use the media file online upload feature.
To begin, click [here](https://cloudinary.com/console) to set up a new account or log into an existing one. We use the environment keys from the user dashboard to intergrate Cloudinary with our project. We will create a file named `env` and use the guide below to fill in the project configuration.

```
      CLOUDINARY_NAME=
      CLOUDINARY_API_KEY=
      CLOUDINARY_API_SECRET=
      GOOGLE_API_KE=
```


Our app's home component will include 2 sections; `html` and `php`. Start by including the following in the `index.php` directory

```php
"index.php"


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
```

The code above creates a webpage titled `Website screenshot` and also imports bootstrap for its css features. We want a user to be able to fill a form with any website URL and receive an image containing contents of the webpage. The webpage should also be backed up using cloudinary online feature. The UI looks like the below:

![complete UI](https://res.cloudinary.com/dogjmmett/image/upload/v1655397544/UI_bcnj3m.png "complete UI")

Now to make the components function.

## Cloudinary
[Cloudinary](https://cloudinary.com/?ap=em) reffers an end-to-end image- and video-management solution for websites and mobile apps, covering everything from image and video uploads, storage, manipulations, optimizations to delivery.Our app will use the media file online upload feature.
To begin, click [here](https://cloudinary.com/console) to set up a new account or log into an existing one. We use the environment keys from the user dashboard to intergrate Cloudinary with our project. We willCreate a file named `env` and use the guide below to fill in the project configuration.

```
      CLOUDINARY_NAME=
      CLOUDINARY_API_KEY=
      CLOUDINARY_API_SECRET=
      GOOGLE_API_KE=
```
Use autoload to load all the dependancies install with php composer

```php
"index.php"


<?php
include_once __DIR__ . '/vendor/autoload.php';
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
 
?>
 
```

Confirm that the get request has the required form variable filled.

```php
"index.php"

if(isset($_GET['site'])){
  
}
```
Use the following  [link](https://developers.google.com/speed/docs/insights/v5/get-started) to 
Generate the google API key for website insights.
Attache the api key to the environmental variable so as to use it in future and ensure security of the application secrets.

```php
"index.php"

if(isset($_GET['site'])){
  $api =getenv("GOOGLE_API_KE");
    $site =$_GET['site'];
}
```

Build url to send the request to capture the website details to google
using the google api key together with the user inputed url for the site.

```php
"index.php"

$adress="https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$site&category=CATEGORY_UNSPECIFIED&strategy=DESKTOP&key=$api";
```

Initialize the curl request with the earlier generated url. In preparation for sending a get request to google with the site url.

```php
"index.php"



$curl_init = curl_init($adress);
```
Setup curls options for the get request
 
```php
"index.php"

curl_setopt($curl_init,CURLOPT_RETURNTRANSFER,true);
```
Execute the curl request and capture the curl response for extraction of the website details
specifically the screenshot.

```php
"index.php"


$response = curl_exec($curl_init);
```
It is always a good practice to close the curl channels to avoid hoarding of server resources.
after response has been recieved.
```php
"index.php"

curl_close($curl_init);
```

 Decode the json response recieved into a key value pair php array.
 ```php
"index.php"

 $googledata = json_decode($response,true);

```
Extract image data from the decoded response to get.

```php
"index.php"

$snapdata = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"];
```
 
Isolate the captured screenshot from the data extracted on previous section.

   ```php
"index.php"

$snap =$snapdata["screenshot"];
```
Initialize cloudinary instance gloabally across the application with secret keys from the env 

```php
 "index.php"

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
```
 upload the screenshot base64 data to cloudinary for storage and capture the response to display on 
 UI
```php
 "index.php"

  	$response2 = (new UploadApi())->upload($snap['data'], [
		'resource_type' => 'image',
		]
	);
```
Thats it! your succesfully able to capture images from Web URL. enjoy the experience.
