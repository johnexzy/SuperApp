<?php
namespace Src\Layout;
class NavBarClass
{
   private $root = null;
    private $active = null;
    public function __construct($root, $active)
    {
        $this->root = $root;
        $this->active = $active;
    }

    public function returnNavLayout()
    {
        return '
        <head>
            <meta charset="utf-8" />
            <link rel="apple-touch-icon" sizes="76x76" href="'.$this->root.'assets/img/favicon.ico">
            <link rel="icon" type="image/png" href="'.$this->root.'assets/img/favicon.ico">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
            <title>LeccelGist</title>
            <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />
            <!-- Google Font -->
            <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Source+Sans+Pro:400,600,700" rel="stylesheet">
            <!-- Font Awesome Icons -->
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" 
            integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
            <!-- Main CSS -->
            <link href="'.$this->root.'assets/css/main.css" rel="stylesheet" />
        </head>
        <body>
    <!--------------------------------------
NAVBAR
--------------------------------------->
    <nav class="topnav navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="'.$this->root.'"><strong>LeccelGist</strong></a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
	</button>
            <div class="navbar-collapse collapse" id="navbarColor02" style="">
                <ul class="navbar-nav mr-auto d-flex align-items-center">
                    <li class="nav-item active">
                        <a class="nav-link" href="'.$this->root.'">Intro <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.$this->root.'article.html">Culture</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.$this->root.'article.html">Tech</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.$this->root.'article.html">Politics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.$this->root.'article.html">Health</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.$this->root.'article.html">Collections</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.$this->root.'about.html">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="'.$this->root.'docs.html">Template <span class="badge badge-secondary">docs</span></a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto d-flex align-items-center">
                    <li class="nav-item highlight">
                        <a class="nav-link" href="#">ADVERTISE WITH US</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    ';
    }
}
