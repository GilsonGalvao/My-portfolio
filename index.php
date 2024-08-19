<!doctype html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="Web Development Portfolio">
    <meta name="author" content="Gilson Galvão">
    <meta name="keywords" content="web development, portfolio">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gilson Galvão - Web Development</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/index.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/b4192a5e80.js" crossorigin="anonymous"></script>  
    <script>
      $(document).ready(function() {
        $('.portfolio-item').click(function() {
          const projectTitle = $(this).data('title');
          const projectImage = $(this).data('image');
          const projectDescription = $(this).data('description');

          $('#projectModalLabel').text(projectTitle);
          $('#projectModalImage').attr('src', projectImage);
          $('#projectModalDescription').text(projectDescription);

          $('#projectModal').modal('show');
        });

        $("#prazo").on("input", calcularDesconto)
        $("#pageType").on("change", calcularDesconto)
        $(".form-check-input").on("change",calcularDesconto)
        
        $("#carregarNoticias").click(function(){
          $("#rss-feed").toggle();
          
          if($("#rss-feed").is(":visible")){
            carregarNoticias();
          }
        });
      });

        function carregarNoticias (){
          $.ajax({
            url: "load_news.php",
            method: "GET",
            dataType: "json",
            success: function(data){
              console.log("Data received:", data); // Adicionado para depuração
              const feedContainer = $("#rss-feed");
              feedContainer.empty();
              data.forEach(function(item){
                const a = $('<a></a>').attr('href', 'news_detail.php?id='+item.ID).text(item.Titulo).attr('target', '_blank').addClass('dropdown-item');
                feedContainer.append(a);
              });
            },
            error: function(jqXHR, textStatus, errorThrown){
              console.log("Error details: ", textStatus, errorThrown);//Adicionando para depuração
              console.log("Response Text: ", jqXHR.responseText);
              alert("Error loading news!");
            }
          });
        }
    function calcularDesconto() {
        let prazo = parseInt(document.getElementById('prazo').value) || 0;
        let maxDesconto = 20; 
        let desconto = Math.min(prazo * 5, maxDesconto); 
        
        let pageTypeSelect = document.getElementById('pageType');
        let valorInicial = parseFloat(pageTypeSelect.options[pageTypeSelect.selectedIndex].value) || 0;
        
        let checkboxes = document.querySelectorAll(".form-check-input:checked")
        let valorCheckboxes = checkboxes.length * 400

        let valorComDesconto = (valorInicial + valorCheckboxes) * (1 - desconto / 100);
        
        document.getElementById('valorFinal').value = valorComDesconto.toFixed(2);
    }


    </script>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #25318cff;">
      <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">GILSON GALVÃO</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav navbar-nav-center mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php">HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#portfolio">MY PROJECTS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#budget">BUDGET</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="find_me.html" target="_blank">FIND ME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#register">REGISTER</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.html" target="_blank">LOGIN</a>
            </li>
          </ul>

          <ul class="navbar-nav ms-auto">
            
            <li class="nav-item dropdown">
              <button id="carregarNoticias" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Load news here</button>
              <ul id="rss-feed" class="dropdown-menu" aria-labelledby="carregarNoticias">

              </ul>
            </li>
          </ul>  
        </div>
      </div>
    </nav>

    <div class="content-wrapper">

      <div class="container my-4">

        <div class="highlight text-center">
          <h2>Welcome to my web development portfolio</h2>
          <p>Check out some of my achievements and projects below</p>
        </div>

        <div class="highlight" id="apresentacao">
          <div class="row align-items-center">
            <div class="col-12  col-md-6 mb-4 mb-md-0">
              <img id="myphoto" src="image/perfil.jpeg" alt="my photo" class="img-fluid">
            </div>
            <div class="col-12 col-md-6 aboutMe">
              <p>Hi. I'm Gilson Galvão, nice to meet you.
              <br>I am passionate about building 
              <br>excellent software that 
              <br>improves the lives 
              <br>of those.</p>
            </div>
          </div>
        </div>

        <div class="highlight" id="description-area">
          <div class="row text-center">
            <div class=" col-12 col-md-4 icon">
              <span><i class="fa-solid fa-heart fa-3x"></i></span>
              <p class="description">Build with love</p>
            </div>
            <div class=" col-12 col-md-4 icon">
              <span><i class="fa-solid fa-hammer fa-3x"></i></span>
              <p class="description">I transform your physical store into an e-commerce.</p>
            </div>  
            <div class=" col-12 col-md-4 icon">
              <span><i class="fa-solid fa-pen fa-3x"></i></span>
              <p class="description">Tailored to your needs</p>
            </div>  
          </div>
        </div>



      <div class="highlight" id="portfolio">
        <h3 class="text-center mb-4" style="text-transform: uppercase;">My projects</h3>
        <div class="row">
          <?php 
            include "config.php";
            $sql = "SELECT * FROM Projetos ORDER BY Data_criacao DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0){
              while($row = $result->fetch_assoc()){
                echo '<div class="col-12 col-md-4 mb-4">';
                echo '<div class="card portfolio-item" data-title="'.$row["Titulo"].'" data-image="'.$row["Imagem"].'" data-description="'.$row["Descricao"].'">';
                echo '<img src="'.$row["Imagem"].'" class="card-img-top fixed-height-image" alt="'.$row["Titulo"].'">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">'.$row["Titulo"].'</h5>';
                echo '<p class="card-text">Tecnologia: '.$row["Tecnologia"].'</p>';
                echo '<p class="card-text">Tempo de Conclusão: '.$row["Tempo_conclusao"].'';
                echo '</div>';
                echo '</div>';
                echo '</div>';
              }
            } else{
                echo "<p>No projects found!</p>";
            }
            $conn->close();
          ?>        
        </div>
      </div>

      <div class="highlight" id="news">
          <h3 class="text-center mb-4" style="text-transform: uppercase;">Latest News</h3>
          <div class="row">
            <?php
              include "config.php";
              $sql = "SELECT * FROM Noticias ORDER BY Data_publicacao DESC LIMIT 5";
              $result = $conn->query($sql);
              if ($result->num_rows > 0){
                while ($row = $result->fetch_assoc()){
                  echo '<div class="col-12 col-md-4 mb-4">';
                  echo '<div class="card news-item">';
                  if($row["Imagem"]){
                    echo '<img src="'.$row["Imagem"].'" class="card-img-top fixed-height-image" alt="'.$row["Titulo"].'">';
                  }
                  echo '<div class="card-body">';
                  echo '<h5 class="card-title">'.$row["Titulo"].'</h5>';
                  echo '<p class="card-text">'.substr($row["Conteudo"],0,100).'...</p>';
                  echo '<a href="news_detail.php?id='.$row['ID'].'" class="btn btn-primary">Read more</a>';
                  echo '</div>';
                  echo '</div>';
                  echo '</div>';
                }
              } else{
                echo "<p> No News found!</p>";
              }
              $conn->close();
            ?>
          </div>
      </div>
      
      <div class="highlight" id="budget">
        <h3 class="text-center mb-4" style="text-transform: uppercase;">MAKE YOUR BUDGET AND REGISTER</h3>
        <div class="row d-flex justify-content-center">
          <div class="col-12  col-md-6 mb-4 mb-md-0">
            <form id="form" action="register.php" method="post">
              <h4>Personal Data</h4>
              <label for="firstName">Name: </label>
              <input type="text" id="firstName" name="firstName" autocomplete="given-name" required>
              <br>
              <label for="lastName">Surname: </label>
              <input type="text" id="lastName" name="lastName" autocomplete="family-name" required>
              <br>
              <label for="phone">Phone: </label>
              <input type="text" id="phone" name="phone" autocomplete="tel" required>
              <br><br>
              <h4>Request your quote</h4>
              <label for="pageType">Type of page: </label>
              <select name="pageType" id="pageType" required>
                <option value="">Choose the type of page</option>
                <option value="100">Landing Page - 100,00€</option>
                <option value="120">Blog - 120,00€</option>
                <option value="200">Corporate website - 200,00€</option>
                <option value="300">E-commerce - 300,00€</option>
              </select>
              <br><br>
              <label for="prazo">Delivery time in months: </label>
              <input type="number" name="prazo" id="prazo" min="1" required>
              <br><br>
              <div class="form-check">
                <p><b>Select the desired tabs</b></p>
                <input class="form-check-input" type="checkbox" id="quem-somos" name="sections[]" value="quem-somos">
                <label class="form-check-label" for="quem-somos">Who we are</label><br>
                <input class="form-check-input" type="checkbox" id="onde-estamos" name="sections[]" value="onde-estamos">
                <label class="form-check-label" for="onde-estamos">Where we are</label><br>
                <input class="form-check-input" type="checkbox" id="galeria-fotos" name="sections[]" value="galeria-fotos">
                <label class="form-check-label" for="galeria-fotos">Photo gallery</label><br>
                <input class="form-check-input" type="checkbox" id="ecommerce" name="sections[]" value="ecommerce">
                <label class="form-check-label" for="ecommerce">E-commerce</label><br>
                <input class="form-check-input" type="checkbox" id="gestao-interna" name="sections[]" value="gestao-interna">
                <label class="form-check-label" for="gestao-interna">Internal management</label><br>
                <input class="form-check-input" type="checkbox" id="noticias" name="sections[]" value="noticias">
                <label class="form-check-label" for="noticias">News</label><br>
                <input class="form-check-input" type="checkbox" id="redes-sociais" name="sections[]" value="redes-sociais">
                <label class="form-check-label" for="redes-sociais">Social media</label><br>
              </div>
              <br><br>
              <p><b>Estimated budget</b></p>
              <p>Obs.: This figure is purely indicative and may change</p>
              <input type="text" id="valorFinal" name="valorFinal" readonly>      
              <br><br>
              <p id="register"><b>Register your login</b></p>
              <label for="email">Email: </label>
              <input type="email" id="email" name="email" autocomplete="email" required>
              <br>
              <label for="username">Username: </label>
              <input type="text" id="username" name="username" autocomplete="username" required>
              <br>
              <label for="password">Password: </label>
              <input type="password" id="password" name="password" autocomplete="new-password" required>
              <br><br>
              <button type="submit" class="btn btn-primary">Send</button>
            </form>
          </div>
        </div>
      </div>

    </div>

      <div class="container" id="footer">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
          
          <div class="col-12 col-md-4 align-items-center text-center mb-3 mb-md-0">
            <h4>Email</h4>
            <div class="icon-container">
              <a href="mailto:gilsongalvao@outlook.pt"><i class="fa-solid fa-envelope fa-3x" style="color: white;"></i></a>
            </div>
            <p>gilsongalvao@outlook.pt</p>

          </div>

          <div class="col-12 col-md-4 align-items-center text-center mb-3 mb-md-0">
            <h4>Whatsapp</h4>
            <div class="icon-container">
              <a href="https://wa.me/+351938763401"><i class="fa-brands fa-whatsapp fa-3x" style="color: white;" ></i></a>
            </div>
            <p>+351 938 763 401</p>
          </div>

          <div class="col-12 col-md-4 align-items-center text-center mb-3 mb-md-0">
            <h4>LinkedIn</h4>
            <div class="icon-container">
              <a href="https://www.linkedin.com/in/gilsongalvao" target="_blank"><i class="fa-brands fa-linkedin fa-3x" style="color: white;"></i></a>
            </div>
            <p>www.linkedin.com/in/gilsongalvao</p>
          </div>

        </footer>
      </div>
    </div>

    <div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="projectModalLabel">Project Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <img id="projectModalImage" src="" alt="Projeto Imagem" class="img-fluid">
                <p id="projectModalDescription" class="mt-3">Project description</p>
              </div>
            </div>
          </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TI7AeoM79WvnB68HAt1wNH8e4fRv4rkndJz9tU8/d0WdxPSQnLAs" crossorigin="anonymous"></script>
  </body>
</html>