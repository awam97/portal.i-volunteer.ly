<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم التسجيل بنجاح</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
        }
        .container h1 {
            color: #87A052;
            margin-bottom: 20px;
        }
        .container p {
            color: #555;
            margin-bottom: 30px;
        }
        .container a {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #304300;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .container a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>تم التسجيل بنجاح !</h1>
        <p>شكراً لتسجيلك معنا في منصة أنا متطوع ! ، يمكنك الآن تسجيل دخولك باستخدام بيانات الدخول التي قمت بتعيينها</p>
        <a href="<?= base_url('login') ?>">تسجيل الدخول</a>
    </div>
</body>
</html>
