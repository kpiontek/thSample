<html>
<head>
<title>Theme Hub - Wordpress, Drupal, PSD, HTML themes</title>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
<link href="/assets/css/normalize.css" media="all" rel="stylesheet" />
<link href="/assets/css/main.css" media="all" rel="stylesheet" />
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="/assets/js/main.js"></script>
</head>

<body>
<header>
  <a href="/"><img src="/assets/img/logo.png" width="300px" height="50px" id="logo" /></a>
  
  <ul class="mainMenu">
    <li><a href="/">Home</a></li>
    <li><a href="/themes/popular">Popular</a></li>
    <?php foreach($data['categories'] as $pid => $category): ?>
    <li><a href="<?= $category['url']; ?>"><?= $category['title']; ?></a>
      <?php if (isset($category['children'])): ?>
      <ul>
        <?php foreach($category['children'] as $cid => $child): ?>
        <li><a href="<?= $child['url']; ?>"><?= $child['title']; ?></a></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
  
</header>
<h1><?php if (isset($data['page_title'])) echo $data['page_title']; ?></h1>
<div class="content">