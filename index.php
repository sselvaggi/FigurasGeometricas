<?php
// Model
class FiguraFactory {

    public static function construirFigura($tipo, $a1, $a2 = null, $a3 = null) {
        $result;
        switch ($tipo) {
            case 'circulo':
                $result = static::construirCirculo($a1);
                break;
            case 'cuadrado':
                $result = static::construirCuadrado($a1);
                break;
            case 'triangulo':
                $result = static::construirTriangulo($a1, $a2, $a3);
                break;
            
            default:
                throw new Exception("Tipo de figura desconocido o inválido", 1);
                break;
        }
        return $result;
    }

	public static function construirCirculo($radio) {
		return new Circulo($radio);
	}
	public static function construirTriangulo($lado1, $lado2, $lado3) {
		return new Triangulo($lado1, $lado2, $lado3);
	}
	public static function construirCuadrado($lado) {
		return new Cuadrado($lado);
	}
}

abstract class Figura {
    public abstract function generarSVG();
}

class Circulo extends Figura {
	protected $radio;
	public function __construct($radio) {
		$this->radio = $radio;
	}
    public function generarSVG() {
        return '<svg height="1000" width="100%">'.
        '<circle cx="'.$this->radio.'" cy="'.$this->radio.'" r="'.$this->radio.'"  fill="red" />'.
        '</svg>';
    }
}

class Triangulo extends Figura {
	protected $lado1;
	protected $lado2;
	protected $lado3;

	public function __construct($lado1, $lado2, $lado3) {
		$this->lado1 = $lado1;
		$this->lado2 = $lado2;
		$this->lado3 = $lado3;
	}

    public function generarSVG() {
        return '<svg height="1000" width="100%">'.
        '<polygon points="200,10 250,190 160,210" style="fill:lime;stroke:purple;stroke-width:1" />'
        .'</svg>';
    }
}

class Cuadrado extends Figura {
	protected $lado;
	public function __construct($lado) {
		$this->lado = $lado;
	}
    public function generarSVG() {
        return '<svg width="100%" height="1000px">
  <rect width="'.$this->lado.'" height="'.$this->lado.'" style="fill:rgb(0,0,255);stroke-width:3;stroke:rgb(0,0,0)" />
</svg>';
    }
}

// Constroller

$errors = array();
$figura;
if (!isset($_POST['generar'])) {

} else if ($_GET['figura'] === 'circulo') {
	$radio = isset($_POST['radio']) ? $_POST['radio'] : '';
    if ($radio > 500 || $radio < 1) {
        $errors[] = "Las medidas deben estar entre 1 y 500 px";
    }
    if (!is_integer($radio*1)) {
        $errors[] = "El radio debe ser un número entero";
    }
    $figura = FiguraFactory::construirCirculo($radio);
} else if ($_GET['figura'] === 'triangulo') {
	$lado1 = isset($_POST['lado1']) ? $_POST['lado1'] : '';
	$lado2 = isset($_POST['lado2']) ? $_POST['lado2'] : '';
	$lado3 = isset($_POST['lado3']) ? $_POST['lado3'] : '';
    
    if ($lado1 > 500 || $lado2 > 500 || $lado3 > 500 ||
        $lado1 < 1 || $lado2 < 1 || $lado3 < 1) {
        $errors[] = "Las medidas deben estar entre 1 y 500 px";
    }
    if (!is_integer($lado1*1) || !is_integer($lado2*1) || !is_integer($lado3*1)) {
        $errors[] = "Las medidas deben ser números enteros";
    }
    $figura = FiguraFactory::construirTriangulo($lado1, $lado2, $lado3);
} else if ($_GET['figura'] === 'cuadrado') {
	$lado = isset($_POST['lado']) ? $_POST['lado'] : '';
    $figura = FiguraFactory::construirCuadrado($lado);
    if ($lado > 500 || $lado < 1) {
        $errors[] = "Las medidas deben estar entre 1 y 500 px";
    }
    if (!is_integer($lado*1)) {
        $errors[] = "Las medidas deben ser un números enteros";
    }
}


// View

?><!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Geometría</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <h1>¿Qué Figura deseas construir?</h1>
        <ul>
        	<li>
        		<a href="?figura=circulo">Circulo</a>
        	</li>
        	<li>
        		<a href="?figura=triangulo">Triángulo</a>
        	</li>
        	<li>
        		<a href="?figura=cuadrado">Cuadrado</a>
        	</li>
        </ul>
        <?php
    	if (isset($_GET['figura'])) {
    	?>
        <form method="post">
        	<?php
        	if ($_GET['figura'] === 'circulo') {
        	?>
        	<label for="radio">Radio: </label>
        	<input id="radio" type="number" name="radio" value="<?php echo $radio ?>"> px
        	<?php
        	} else if ($_GET['figura'] === 'triangulo') {
        	?>
        	<label for="lado1">Lado1: </label>
        	<input id="lado1" type="number" name="lado1" value="<?php echo $lado1 ?>"> px
        	<br>
        	<label for="lado2">Lado2: </label>
        	<input id="lado2" type="number" name="lado2" value="<?php echo $lado2 ?>"> px
        	<br>
        	<label for="lado3">Lado3: </label>
        	<input id="lado3" type="number" name="lado3" value="<?php echo $lado3 ?>"> px
        	<?php
        	} else if ($_GET['figura'] === 'cuadrado') {
        	?>
        	<label for="lado">Lado: </label>
        	<input id="lado" type="number" name="lado" value="<?php echo $lado ?>"> px
        	<?php
        	} ?>
        	<br>
        	<input type="submit" name="generar" value="Construir!">
        </form>
        <?php
    	}
        if (!count($errors) && isset($figura)) {
            echo $figura->generarSVG();
        }
    	?>
        <small style="position:fixed;right: 1em;bottom: 1em">Thanks HTML5 Boilerplate.</small>



        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script type="text/javascript">
            var errors = <?php echo json_encode($errors)?>;
            errors.forEach(function(error) {
                alert(error); 
            });
        </script>
    </body>
</html>