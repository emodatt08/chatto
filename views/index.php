<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../public/css/bootstrap.css" class="rel">

    <title>Chatto</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-push-2 col-md-8">
                <h2>Chatto</h2>
                <h3>Messages For <span class="username label label-primary"></span></h3>
                <form action="#" class="username-setter" method="POST">
                    <div class="form-group">
                        <label for="set-message">Set username</label>
                        <input type="text" name="name" id="set-message" class="form-control username-input" value="">
                    </div>

                    <button class="btn btn-primary pull-right" type="submit" name="button">Set</button>
                </form>


                <h3>Messages</h3>
                <ul class="message-list">
                
                </ul>
                <form action="" class="chat-form" method="POST">
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea type="button" name="message" id="message" class="form-control" value=""></textarea>
                </div>
                <div class="nothing">
                    <button class="btn btn-primary pull-right">Send</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../public/js/jquery.js">
    </script>
    <script src="../public/js/jquery.cookie.js">
    </script>
    <script src="../public/js/main.js">
    </script>
    
</body>
</html>