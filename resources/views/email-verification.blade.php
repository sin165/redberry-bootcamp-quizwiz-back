@props(['url', 'logo', 'username'])

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f0f0;
    }
    .container {
      max-width: 448px;
      margin: 40px auto;
    }
    .header img {
      display: block;
      margin: 0 auto;
    }
    .heading {
      font-family: 'Raleway', sans-serif;
      text-align: center;
      margin-top: 24px;
      font-size: 40px;
      font-weight: 700;
      line-height: 1.5;
    }
    .content {
      margin-top: 26px;
      line-height: 1.6;
    }
    p:first-child {
      padding-left: 10px;
    }
    .button {
      display: block;
      width: max-content;
      max-width: max-content;
      margin: 26px auto;
      padding: 10px 52px;
      background-color: #4B69FD;
      text-decoration: none;
      border-radius: 10px;
      font-weight: 600;
    }
    .button span {
      color: #FFFFFF;
    }
    .button:hover {
      box-shadow: 0px 8px 10px 0px #00000033, 0px 6px 30px 0px #0000001F, 0px 16px 24px 0px #00000024;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="{{ $logo }}" alt="QuizWiz logo">
    </div>
    <h1 class="heading">Verify your email address to get started</h1>
    <div class="content">
      <p>Hi {{ $username }},</p>
      <p>You're almost there! To complete your sign up, please verify your email address.</p>
      <a href="{{ $url }}" class="button"><span>Verify now</span></a>
    </div>
  </div>
</body>
</html>
