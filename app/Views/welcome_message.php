<html>
    <head>
        <link  rel="icon" href="<?= base_url('/public/assets/images/chikara logo.png'); ?>" type="image/png" />
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <style>
        body {
            text-align:center;
        }
        section {
            position: relative;
            background-color: black;
            height: 100vh;
            min-height: 25rem;
            width: 100%;
            overflow: hidden;
        }

        section video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 0;
            -ms-transform: translateX(-50%) translateY(-50%);
            -moz-transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
        }

        section .container {
            position: relative;
            z-index: 2;
        }

        section .overlay-wcs {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: black;
            opacity: 0.5;
            z-index: 1;
        }

        img {
            height: 100%;
        }
    </style>

    <body>
        <section >
            <div class="overlay-wcs"></div>
            <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
              <source src="<?= base_url(ASSETS_BASE_URL.'/videos/definitivo.mp4'); ?>" type="video/mp4">
            </video>
            <div class="container h-100">
              <div class="d-flex h-100 text-center align-items-center">
                <div class="w-100 text-white">
                    <h1>ようこそ</h1> 
                  <p class="lead mb-0">Bienvenidos!</p>
                </div>
              </div>
            </div>
          </section>
    </body>

</html>
