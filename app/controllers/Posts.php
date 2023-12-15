<?php

class Posts extends Controller
{
    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index($page = 1)
    {
        // Number of posts per page
        $postsPerPage = 10;

        // Get total number of posts
        $totalPosts = $this->postModel->getTotalPosts();

        // Calculate total number of pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Validate page number
        if ($page < 1 || $page > $totalPages) {
            $page = 1;
        }

        // Get selected filters
        $brand = isset($_GET['brand']) ? $_GET['brand'] : null;
        $model = isset($_GET['model']) ? $_GET['model'] : null;
        $year = isset($_GET['year']) ? $_GET['year'] : null;

        // Получите посты для указанной страницы и фильтров
        if ($brand !== null || $model !== null || $year !== null) {
            // Если есть параметры фильтрации, использовать метод getFilteredPosts
            $posts = $this->postModel->getFilteredPosts($brand, $model, $year, $page, $postsPerPage);
            $totalPosts = $this->postModel->getTotalFilteredPosts($brand, $model, $year);
            // Calculate total number of pages
            $totalPages = ceil($totalPosts / $postsPerPage);
        } else {
            // Else use basic getPosts without filtering
            $posts = $this->postModel->getPosts($page, $postsPerPage);
        }

        $brands = $this->postModel->getBrands();
        $years = $this->postModel->getYears();
        $models = $this->postModel->getModels();
        $images = $this->postModel->getImages($_SESSION['user_id']);

        $data = [
            'brand' => $brand,
            'model' => $model,
            'models' => $models,
            'year' => $year,
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'brands' => $brands,
            'years' => $years,
            'images' => $images,
            'image' => $images,
        ];

        $this->view('posts/index', $data);
    }


    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $brands = $this->postModel->getBrands();
            $years = $this->postModel->getYears();
            $target_dir = PUBROOT;
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
// Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

// Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

// Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

// Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

            $data = [
                'title' => isset($_POST['title']) ? trim($_POST['title']) : '',
                'user_id' => $_SESSION['user_id'],
                // 'body' => isset($_POST['body']) ? trim($_POST['body']) : '',
                'brand' => isset($_POST['brand']) ? trim($_POST['brand']) : '',
                'model' => isset($_POST['model']) ? trim($_POST['model']) : '',
                'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
                'year' => isset($_POST['year']) ? trim($_POST['year']) : '',
                'image_path' => $target_file,
                'brands' => $brands,
                'years' => $years,
                'title_err' => '',
                // 'body_err' => '',
                'brand_err' => '',
                'model_err' => '',
                'description_err' => '',
                'year_err' => '',
            ];


            // Validate data
            $isError = false;
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
                $isError = true;
            }
            // if (empty($data['body'])) {
            //     $data['body_err'] = 'Please enter body text';
            //     $isError = true;

            // }
            if (empty($data['brand'])) {
                $data['brand_err'] = 'Please select a brand';
                $isError = true;

            }
            if (empty($data['model'])) {
                $data['model_err'] = 'Please enter a model';
                $isError = true;

            }
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter a description';
                $isError = true;

            }
            if (empty($data['year'])) {
                $data['year_err'] = 'Please select a year';
                $isError = true;

            }

            // Check for errors before attempting to add a post
            if (!$isError) {
                // Attempt to add the post
                if ($this->postModel->addPost($data)) {
                    flash('post_message', 'Post Added');
                    redirect('posts');
                } else {
                    die('Something went wrong while adding the post');
                }
                echo "Data to be inserted: ";

                // if ($this->postModel->addPost($data)) {
                //     flash('post_message', 'Post Added');
                //     redirect('posts');
                // } else {
                //     die('Something went wrong');
                // }
                // } else {
                //     // Load view with errors
                //     $this->view('posts/add', $data);
                // }

            } else {
                // Load view with errors
                $this->view('posts/add', $data);
            }
        } else {
            $brands = $this->postModel->getBrands();
            $years = $this->postModel->getYears();

            $data = [
                'title' => '',
                // 'body' => '',
                'brand' => '',
                'model' => '',
                'description' => '',
                'year' => '',
                'brands' => $brands,
                'years' => $years,
            ];

            $this->view('posts/add', $data);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                // 'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
            ];

            // Validate data
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }
            if (empty($data['body'])) {
                $data['body_err'] = 'Please enter body text';
            }

            // Make sure no errors
            if (empty($data['title_err']) && empty($data['body_err'])) {
                // Validated
                if ($this->postModel->updatePost($data)) {
                    flash('post_message', 'Post Updated');
                    redirect('posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('posts/edit', $data);
            }

        } else {
            // Get existing post from model
            $post = $this->postModel->getPostById($id);

            // Check for owner
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }

            $data = [
                'id' => $id,
                'title' => $post->title,
                // 'body' => $post->body
            ];

            $this->view('posts/edit', $data);
        }
    }

    public function show($id)
    {
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);

        $data = [
            'post' => $post,
            'user' => $user
        ];

        $this->view('posts/show', $data);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get existing post from model
            $post = $this->postModel->getPostById($id);

            // Check for owner
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }

            if ($this->postModel->deletePost($id)) {
                flash('post_message', 'Post Removed');
                redirect('posts');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('posts');
        }
    }
}