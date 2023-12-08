<?php
  class Pages extends Controller {
    public function __construct(){
     
    }
    
    public function index(){
      if(isLoggedIn()){
        redirect('posts');
      }

      $data = [
        'title' => 'Auto Blitz',
        'description' => ' Unleashing the Fast Lane Thrill! Discover a symphony of speed and style at AutoBlitz, where every car is a masterpiece in motion. Your journey to automotive excellence starts here!"'
      ];
     
      $this->view('pages/index', $data);
    }

    public function about(){
      $data = [
        'title' => 'About Us',
        'description' => 'Where you could find your dream vehicle'
      ];

      $this->view('pages/about', $data);
    }
  }