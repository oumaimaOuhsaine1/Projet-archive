<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style/main.css" />
  <link rel="stylesheet" href="style/dash.css" />

  <!-- Font Awesome Cdn Link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
  <style>
    .bg-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('../img/image-5.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      z-index: -2;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: -1;
    }

    .logo-image {
      width: 132px;
      height: 44px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      background: rgba(255, 255, 255, 0.1);
    }

    .row {
      display: flex;
      justify-content: center;
      margin-top: 50px;
    }

    .column {
      margin: 0 20px;
    }

    .card {
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      padding-left: 11px;
      text-align: center;
      background-color: #f1f1f1;
      display: block;
      text-decoration: none;
      color: #000;
      border-radius: 8px;
      transition: filter 0.3s, transform 0.3s;
      height: 100px;
      line-height: 68px;
      width: 400px;
    }

    .cardS {
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      padding-left: 11px;
      text-align: center;
      background-color: #f1f1f1;
      display: block;
      text-decoration: none;
      color: #000;
      border-radius: 8px;
      transition: filter 0.3s, transform 0.3s;
      height: 100px;
      line-height: 68px;
      width: 468px;
      margin-top: 47px;
    }
  </style>
</head>
<body>
  <div class="bg-overlay"></div>
  <div class="overlay"></div>

  <header class="header">
    <button>
      <a href="index.php" class="icon-btn"><i class="fas fa-arrow-left"></i> Retour</a>
    </button>
    <div class="logo">
      <a href="index.php">
        <img src="../img/logo_rh.png" alt="Logo" class="logo-image">
      </a>
    </div>
  </header>

  <div class="row">
    <div class="column">
      <a href="parametrage/boite.php" class="card">
        <div>Boîte</div>
      </a>
    </div>
    <div class="column">
      <a href="parametrage/direction.php" class="card">
        <div>Direction</div>
      </a>
    </div>
  </div>

  <div class="row">
    <div class="column">
      <a href="parametrage/salle.php" class="cardS">
        <div>Salle</div>
      </a>
    </div>
  </div>

  <script src="../js/script.js"></script>
</body>
</html>
