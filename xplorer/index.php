<?php
session_start();

// mbstring is old and has it's functions around for older versions of PHP.
// if mbstring is not loaded, we go into native mode.
if (extension_loaded('mbstring')){	mb_internal_encoding('UTF-8');}

$GLOBALS['errors'] = array();

include_once 'apicaller.php';

$apicaller = new ApiCaller('28e336ac6c9423d946ba02d19c6a2632', APISERVER);

$ayas = $apicaller->sendRequest(array(
	'cls' => 'forkan',
	'act' => 'read',
	'ayaID' => 1
));



?><!DOCTYPE html>
<html dir="rtl">
<head>
	<title>الفرقان</title>
    <meta charset="utf-8">
	
	<link rel="stylesheet" href="css/reset.css" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="css/todos.css" media="all" type="text/css"/>
	
    <script src="js/LAB.js"></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script>
		$LAB  
		.script("js/jquery.min.js").wait()  
		.script("js/json2.js")  
		.script("js/jquery.tmpl.min.js")  
		.script("js/underscore-1.1.6.js")  
		.script("js/backbone.js")   
		.script("js/backbone-localstorage.js")   
		.script("js/forkan.js"); 
	</script>
	
	<style>
      /* Override some defaults */
      html, body {
        background-color: #eee;
      }
      body {
        padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
      }
      .container > footer p {
        text-align: center; /* center align it with the container */
      }
      .container {
        width: 820px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
      }

      /* The white background content wrapper */
      .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
        -webkit-border-radius: 0 0 6px 6px;
           -moz-border-radius: 0 0 6px 6px;
                border-radius: 0 0 6px 6px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

      /* Page header tweaks */
      .page-header {
        background-color: #f5f5f5;
        padding: 20px 20px 10px;
        margin: -20px -20px 20px;
      }

      /* Styles you shouldn't keep as they are for displaying this base example only */
      .content .span10,
      .content .span4 {
        min-height: 500px;
      }
      /* Give a quick and non-cross-browser friendly divider */
      .content .span4 {
        margin-right: 0;
        padding-right: 19px;
        border-right: 1px solid #eee;
      }

      .topbar .btn {
        border: 0;
      }
	</style>    
	<!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">

    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
</head>
<body>
	<div class="topbar">
		<div class="fill">
			<div class="container">
				<a class="brand" href="index.php" style="width: 80px;height: 20px;background:url(forkan.png) center center no-repeat;" /></a>          
				<ul class="nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
				<form action="" class="pull-right">
					<input class="input-small" type="text" placeholder="Username">
			
					<input class="input-small" type="password" placeholder="Password">
					<button class="btn" type="submit">Sign in</button>
				</form>
			</div>
		</div>
	</div>
	
    <div class="container">

      <div class="content">
        <div class="page-header">
          <h1>Page name <small>Supporting text or tagline</small></h1>
        </div>
        <div class="row">
          
          <div class="span10">
           <div class="span9" style="text-align: right;">
           <ul>
           <?php
           if(count($GLOBALS['errors'])> 0){foreach($GLOBALS['errors'] as $error): ?>
             <li><span class="label warning">Error</span><?php echo $error; ?></li>
		   <?php endforeach;} ?>
           </ul>
           </div>
           <div id="todolist">
			<?php if($ayas != null){foreach($ayas as $aya):?>
			<span><?php echo $aya[3]; ?></span>  <span class="label success"><?php echo $aya[2]; ?></span>
			<div>

			</div>
			<?php endforeach; }?>
		</div>
    <!-- Todo App Interface -->            
    <div id="todoapp">

      <div class="title">
        <h1>Todos</h1>
      </div>

      <div class="content">

        <div id="create-todo">
          <input id="new-todo" placeholder="What needs to be done?" type="text" />
          <span class="ui-tooltip-top" style="display:none;">Press Enter to save this task</span>
        </div>

        <div id="todos">
          <input class="check mark-all-done" type="checkbox"/>
          <label for="check-all">Mark all as complete</label>
          <ul id="todo-list"></ul>
        </div>

        <div id="todo-stats"></div>

      </div>

    </div>

	
  

          </div>
          <div class="span4">
            <h3>Secondary content</h3>
          </div>
        </div>
      </div>

      <footer>
        <p>&copy; itkane.com 2012. by Jnom23.</p>

      </footer>

    </div> <!-- /container -->	



    <!-- Templates -->

    <script type="text/template" id="item-template">
      <div class="todo <%= done ? 'done' : '' %>">
        <div class="display">
          <input class="check" type="checkbox" <%= done ? 'checked="checked"' : '' %> />
          <label class="todo-content"><%= content %></label>
          <span class="todo-destroy"></span>
        </div>
        <div class="edit">
          <input class="todo-input" type="text" value="<%= content %>" />
        </div>
      </div>
    </script>

    <script type="text/template" id="stats-template">
      <% if (total) { %>
        <span class="todo-count">
          <span class="number"><%= remaining %></span>
          <span class="word"><%= remaining == 1 ? 'item' : 'items' %></span> left.
        </span>
      <% } %>
      <% if (done) { %>
        <span class="todo-clear">
          <a href="#">
            Clear <span class="number-done"><%= done %></span>
            completed <span class="word-done"><%= done == 1 ? 'item' : 'items' %></span>
          </a>
        </span>
      <% } %>
    </script>
	</div>
</body>
</html>