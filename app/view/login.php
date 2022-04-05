<html>

<head>
	<title>Σαμαρείτες</title>
	
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    
    
    <script src="http://localhost/glatsis/pub/assets/js/app.js"></script>
    <link href="assets/css/site.css" rel="stylesheet">
</head>
<body>
	<div class="container login-container">
        <div class="row">
            <div class="col-md-6 login-form-1">
                <h3>Καλώς ήρθατε</h3>
                <form id="login-form" method="POST">
                	<div id="messages" class="error"></div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Your Email *" value="" name="username"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Your Password *" value="" name="password"/>
                    </div>
                    <div class="form-group">
                        <button class="btnSubmit" onclick="login('<?= url('user', 'login') ?>'); return false;">Σύνδεση</button>
                    </div>
                    
                </form>
            </div>
        
          
        </div>
    </div> 
</body> 
</html>