<?php

//	ini_set("display_errors","1");
//	ERROR_REPORTING(E_ALL);

/**
 * get_title
 * Returns a string to be used as page title
 *
 * @param String $page path of internal page
 * @return String HTML page title
 */
function get_title ($page) {
  $pages = explode('/', $page);

  if (count($pages) === 2 && $pages[0] !== "templates") { // We are on an API page

    if (substr($pages[1], -9) === "index.html") { // An API index page
      return $pages[0];
    } else { // A normal API page
      return basename ($pages[1], ".html") . ' &ndash; ' . $pages[0];
    }

  } else {
    return  "Valadoc.org &ndash; Stays crunchy. Even in milk.";
  }
}

// Page parsing
$requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$segments = explode('/', $requestUri);
$first = str_replace(['.html', '.htm'], '', $segments[1] ?? '');
$second = str_replace(['.html', '.htm'], '', $segments[2] ?? '');

if ($first == null || $first === "index") { // Homepage
  $page = "index.htm";
} else if ($second === "index") { // An API index page
  $page = "$first/index.htm";
} else if (file_exists(__DIR__."/$first/$second.html.content.tpl")) { // An API info page
  $page = "$first/$second.html";
} else if (file_exists(__DIR__."/templates/$first.htm.content.tpl")) { // A template page
  $page = "templates/$first.htm";
} else { // 404 page
  header('HTTP/1.1 404 Not Found');
  $page = "templates/404.htm";
}

?>

<!doctype html>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta itemprop="image" content="https://valadoc.org/images/preview.png">
    <meta name="fragment" content="!">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="theme-color" content="#403757">
    <meta property="og:description" content="The canonical source for Vala API references.">
    <meta property="og:image" content="https://valadoc.org/images/preview.png">
    <meta property="og:title" content="<?php echo get_title ($page); ?>">
    <meta property="og:type" content="website">
    <title><?php echo get_title ($page); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400|Droid+Serif:400|Roboto+Mono:400,500,700,400italic">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/styles/main.css" type="text/css">
    <link rel="apple-touch-icon" href="/images/icon.png" />
    <link rel="shortcut icon" href="/images/favicon.ico">
  </head>
  <body>
    <nav class="sidebar">
      <a href="/index.htm" class="logo">
        <img src="/images/logo.svg" alt="Valadoc" height="42px" width="121.75px">
      </a>
      <div class="search">
        <input type="text" placeholder="Search" id="search-field">
      </div>
      <div>
        <ul id="search-results"></ul>
      </div>
      <div id="navigation-content">
        <?php @readfile (__DIR__ . "/" . $page . ".navi.tpl"); ?>
      </div>
    </nav>

    <main>
      <header>
        <div class="sidebar-menu">&#9776;</div>
        <a href="/index.htm" class="logo">
          <img src="/images/logo.svg" alt="Valadoc" height="42px" width="121.75px">
        </a>
      </header>

      <section id="content">
        <?php @readfile (__DIR__ . "/" . $page . ".content.tpl"); ?>
      </section>

      <footer>
        <span>Copyright © <?php echo date('Y'); ?> Valadoc.org | Documentation is licensed under the same terms as its upstream</span>
        <ul>
          <a href="https://github.com/Valadoc/valadoc-org/issues" target="_blank"><li>Issues</li></a>
        </ul>
      </footer>

      <script type="text/javascript" src="/scripts/jquery.min.js"></script>
      <script type="text/javascript" src="/scripts/jquery.ba-hashchange.min.js"></script>
      <script type="text/javascript" src="/scripts/wtooltip.js"></script>
      <script type="text/javascript" src="/scripts/valadoc.js"></script>
      <script type="text/javascript" src="/scripts/main.js"></script>
    </main>
  </body>
</html>
